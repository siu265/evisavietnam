<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Hệ thống Booking Visa V2.5 (Fix Postcode Hiding & Price Display on Return).
Version: 2.5
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Wizard_V2_5 {

    public function __construct() {
        // Frontend Assets & Logic
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'visa_wizard_form', array( $this, 'render_wizard' ) );
        add_action( 'wp_footer', array( $this, 'render_modals_in_footer' ) );
        add_action( 'wp_footer', array( $this, 'custom_checkout_script' ) );

        // Admin
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );

        // AJAX
        add_action( 'wp_ajax_visa_get_price', array( $this, 'ajax_get_price' ) );
        add_action( 'wp_ajax_nopriv_visa_get_price', array( $this, 'ajax_get_price' ) );
        
        add_action( 'wp_ajax_visa_upload_file', array( $this, 'ajax_upload_file' ) );
        add_action( 'wp_ajax_nopriv_visa_upload_file', array( $this, 'ajax_upload_file' ) );
        
        add_action( 'wp_ajax_visa_checkout', array( $this, 'ajax_checkout' ) );
        add_action( 'wp_ajax_nopriv_visa_checkout', array( $this, 'ajax_checkout' ) );
        
        add_action( 'wp_ajax_visa_load_checkout', array( $this, 'ajax_load_checkout' ) );
        add_action( 'wp_ajax_nopriv_visa_load_checkout', array( $this, 'ajax_load_checkout' ) );
        
        add_action( 'wp_ajax_visa_process_checkout', array( $this, 'ajax_process_checkout' ) );
        add_action( 'wp_ajax_nopriv_visa_process_checkout', array( $this, 'ajax_process_checkout' ) );

        // Woo Hooks (Postcode Removal Logic)
        add_filter( 'woocommerce_checkout_fields', array( $this, 'clean_checkout_fields' ), 9999 );
        add_filter( 'woocommerce_default_address_fields', array( $this, 'clean_default_address_fields' ), 9999 );
        
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_order_meta' ), 10, 4 );
        add_action( 'template_redirect', array( $this, 'redirect_cart_page' ) );
        add_action( 'woocommerce_thankyou', array( $this, 'clear_visa_session_on_thankyou' ), 5, 1 );
        
        // Đảm bảo WooCommerce trả về JSON khi submit từ AJAX
        add_filter( 'woocommerce_ajax_get_endpoint', array( $this, 'fix_checkout_endpoint' ), 10, 2 );
    }

    /** Ghi log vào file riêng trong plugin (không phụ thuộc debug.log) */
    private function visa_log( $msg ) {
        try {
            $dir = __DIR__ . '/logs';
            if ( ! is_dir( $dir ) ) {
                wp_mkdir_p( $dir );
            }
            $file = $dir . '/visa-checkout.log';
            $line = '[' . current_time( 'Y-m-d H:i:s' ) . '] ' . ( is_string( $msg ) ? $msg : print_r( $msg, true ) ) . "\n";
            $result = @file_put_contents( $file, $line, FILE_APPEND | LOCK_EX );
            // Nếu không ghi được, thử ghi vào error_log
            if ( $result === false ) {
                error_log( '[VISA LOG FAILED] ' . $msg );
            }
        } catch ( Exception $e ) {
            error_log( '[VISA LOG EXCEPTION] ' . $e->getMessage() );
        }
    }

    /* ================= 1. ADMIN SETTINGS ================= */

    public function add_admin_menu() {
        add_menu_page('Visa Options', 'Visa Options', 'manage_options', 'visa-options', array( $this, 'render_settings_page' ), 'dashicons-admin-site-alt3', 56 );
    }

    public function register_settings() {
        register_setting( 'visa_group', 'visa_nationalities_list' );
        register_setting( 'visa_group', 'visa_work_days' );
        register_setting( 'visa_group', 'visa_work_start' );
        register_setting( 'visa_group', 'visa_work_end' );
        register_setting( 'visa_group', 'visa_schedule_note' );
        register_setting( 'visa_group', 'visa_pricing_notes' );
        register_setting( 'visa_group', 'visa_date_from' );
        register_setting( 'visa_group', 'visa_date_to' );
        register_setting( 'visa_group', 'visa_terms_content' );
        register_setting( 'visa_group', 'visa_privacy_content' );
        register_setting( 'visa_group', 'visa_refund_content' );
        register_setting( 'visa_group', 'visa_terms_checkbox_text' );
    }

    public function admin_styles() {
        echo '<style>.visa-admin-wrap { max-width: 1000px; margin: 20px auto; font-family: sans-serif; } .visa-card { background: #fff; border: 1px solid #ccd0d4; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.05); } .visa-nav-tab-wrapper { margin-bottom: 20px; border-bottom: 1px solid #c3c4c7; } .visa-nav-tab { display: inline-block; padding: 10px 20px; text-decoration: none; color: #555; background: #e5e5e5; margin-right: 5px; border: 1px solid #c3c4c7; border-bottom: none; font-weight: 600; } .visa-nav-tab.active { background: #fff; color: #000; border-bottom: 1px solid #fff; margin-bottom: -1px; } .visa-submit-bar { position: sticky; bottom: 0; background: #fff; padding: 15px; border-top: 1px solid #ccc; z-index: 99; }</style>';
    }

    public function render_settings_page() {
        $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
        ?>
        <div class="wrap visa-admin-wrap">
            <h1>Visa Configuration</h1>
            <div class="visa-nav-tab-wrapper">
                <a href="?page=visa-options&tab=general" class="visa-nav-tab <?php echo $active_tab == 'general' ? 'active' : ''; ?>">General</a>
                <a href="?page=visa-options&tab=nationality" class="visa-nav-tab <?php echo $active_tab == 'nationality' ? 'active' : ''; ?>">Nationality</a>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields( 'visa_group' ); ?>
                <?php
                $days = get_option('visa_work_days', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
                $start = get_option('visa_work_start', '08:30');
                $end = get_option('visa_work_end', '16:30');
                $schedule_note = get_option('visa_schedule_note', 'Show info: Processing time counts from the time the application is confirmed, not submitted, during working hours from {time_from} to {time_to} Vietnam Local Time from {date_from} to {date_to}, except Public Holidays');
                $pricing_notes = get_option('visa_pricing_notes', '');
                $date_from = get_option('visa_date_from', '');
                $date_to = get_option('visa_date_to', '');
                $terms_checkbox_text = get_option('visa_terms_checkbox_text', 'Click to agree: By submitting payment, I acknowledge that I have read and accept the EVISAS VIETNAM Terms of Service, Privacy Policy, and Refund Policy.');
                $current_list = get_option( 'visa_nationalities_list', '' );
                if ( empty( $current_list ) ) $current_list = "Japan\nSouth Korea\nAustralia\nUnited States\nCanada\nChina\nTaiwan\nFrance\nGermany\nNew Zealand";
                ?>
                <div class="visa-tab-panel" id="tab-general" style="<?php echo $active_tab !== 'general' ? 'display:none;' : ''; ?>">
                    <div class="visa-card">
                        <h2>Schedule</h2>
                        <table class="form-table">
                            <tr><th>Days</th><td><?php $all_days = ['Mon'=>'Mon','Tue'=>'Tue','Wed'=>'Wed','Thu'=>'Thu','Fri'=>'Fri','Sat'=>'Sat','Sun'=>'Sun']; foreach($all_days as $key => $label): ?><label style="margin-right:15px;"><input type="checkbox" name="visa_work_days[]" value="<?php echo $key; ?>" <?php if(in_array($key,(array)$days)) echo 'checked'; ?>> <?php echo $label; ?></label><?php endforeach; ?></td></tr>
                            <tr><th>Hours</th><td><input type="time" name="visa_work_start" value="<?php echo esc_attr($start); ?>"> to <input type="time" name="visa_work_end" value="<?php echo esc_attr($end); ?>"></td></tr>
                            <tr><th>Date From</th><td><input type="date" name="visa_date_from" value="<?php echo esc_attr($date_from); ?>" class="regular-text"></td></tr>
                            <tr><th>Date To</th><td><input type="date" name="visa_date_to" value="<?php echo esc_attr($date_to); ?>" class="regular-text"></td></tr>
                            <tr><th>Schedule Note</th><td><textarea name="visa_schedule_note" rows="3" class="large-text code"><?php echo esc_textarea($schedule_note); ?></textarea><p class="description">Use placeholders: {time_from}, {time_to}, {date_from}, {date_to}</p></td></tr>
                        </table>
                    </div>
                    <div class="visa-card">
                        <h2>Pricing Notes</h2>
                        <table class="form-table">
                            <tr><th>Pricing Notes</th><td><textarea name="visa_pricing_notes" rows="5" class="large-text"><?php echo esc_textarea($pricing_notes); ?></textarea></td></tr>
                        </table>
                    </div>
                    <div class="visa-card">
                        <h2>Policies</h2>
                        <div style="margin-bottom:20px;"><label>Terms</label><?php wp_editor( get_option('visa_terms_content'), 'visa_terms_content', ['textarea_rows'=>5,'media_buttons'=>false] ); ?></div>
                        <div style="margin-bottom:20px;"><label>Privacy</label><?php wp_editor( get_option('visa_privacy_content'), 'visa_privacy_content', ['textarea_rows'=>5,'media_buttons'=>false] ); ?></div>
                        <div style="margin-bottom:20px;"><label>Refund</label><?php wp_editor( get_option('visa_refund_content'), 'visa_refund_content', ['textarea_rows'=>5,'media_buttons'=>false] ); ?></div>
                        <div><label>Terms Checkbox Text</label><textarea name="visa_terms_checkbox_text" rows="2" class="large-text"><?php echo esc_textarea($terms_checkbox_text); ?></textarea></div>
                    </div>
                </div>
                <div class="visa-tab-panel" id="tab-nationality" style="<?php echo $active_tab !== 'nationality' ? 'display:none;' : ''; ?>">
                    <div class="visa-card"><h2>Nationalities</h2><textarea name="visa_nationalities_list" rows="20" class="large-text code"><?php echo esc_textarea( $current_list ); ?></textarea></div>
                </div>
                <div class="visa-submit-bar"><?php submit_button('Save Changes', 'primary large', 'submit', false); ?></div>
            </form>
        </div>
        <?php
    }

    /* ================= 2. FRONTEND LOGIC ================= */

    private function get_all_phone_codes() {
        return [ '+84'=>'🇻🇳 VN (+84)', '+1'=>'🇺🇸 US (+1)', '+44'=>'🇬🇧 UK (+44)', '+61'=>'🇦🇺 AU (+61)', '+1'=>'🇨🇦 CA (+1)', '+33'=>'🇫🇷 FR (+33)', '+49'=>'🇩🇪 DE (+49)', '+81'=>'🇯🇵 JP (+81)', '+82'=>'🇰🇷 KR (+82)', '+91'=>'🇮🇳 IN (+91)', '+86'=>'🇨🇳 CN (+86)', '+65'=>'🇸🇬 SG (+65)', '+66'=>'🇹🇭 TH (+66)', '+62'=>'🇮🇩 ID (+62)', '+60'=>'🇲🇾 MY (+60)', '+63'=>'🇵🇭 PH (+63)', '+7'=>'🇷🇺 RU (+7)', '+34'=>'🇪🇸 ES (+34)', '+39'=>'🇮🇹 IT (+39)', '+31'=>'🇳🇱 NL (+31)', '+41'=>'🇨🇭 CH (+41)', '+46'=>'🇸🇪 SE (+46)', '+852'=>'🇭🇰 HK (+852)', '+886'=>'🇹🇼 TW (+886)', '+90'=>'🇹🇷 TR (+90)', '+971'=>'🇦🇪 AE (+971)', '+55'=>'🇧🇷 BR (+55)' ];
    }

    private function get_attribute_label( $slug, $taxonomy ) {
        if ( taxonomy_exists( $taxonomy ) ) {
            $term = get_term_by( 'slug', $slug, $taxonomy );
            if ( $term && ! is_wp_error( $term ) ) return $term->name;
        }
        return ucwords( str_replace( '-', ' ', $slug ) );
    }

    /**
     * Lấy danh sách term slugs theo đúng thứ tự admin (term_order / menu_order / name).
     * $limit_to_slugs: chỉ giữ lại các slug có trong mảng (vd. từ variation attributes).
     */
    private function get_ordered_attribute_term_slugs( $taxonomy, $limit_to_slugs = [] ) {
        if ( ! taxonomy_exists( $taxonomy ) ) {
            return [];
        }
        $try_orderby = array( 'term_order', 'menu_order', 'term_id', 'name' );
        foreach ( $try_orderby as $orderby ) {
            $args = array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'orderby'    => $orderby,
                'order'      => 'ASC',
            );
            $terms = get_terms( $args );
            if ( is_wp_error( $terms ) || empty( $terms ) ) {
                continue;
            }
            $slugs = array_map( function( $t ) { return $t->slug; }, $terms );
            if ( ! empty( $limit_to_slugs ) ) {
                $slugs = array_intersect( $slugs, $limit_to_slugs );
                $slugs = array_values( $slugs );
            }
            return $slugs;
        }
        return [];
    }

    public function enqueue_assets() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css' );
        wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '4.0.13', true );

        $plugin_url = plugin_dir_url( __FILE__ );
        $style_path = plugin_dir_path( __FILE__ ) . 'style.css';
        $version    = file_exists( $style_path ) ? (string) filemtime( $style_path ) : '2.5';
        wp_enqueue_style(
            'e-visa-wizard-style',
            $plugin_url . 'style.css',
            array( 'select2' ),
            $version
        );
        
        // Enqueue WooCommerce checkout scripts nếu cart không rỗng (để hỗ trợ checkout trong step 7)
        if ( ! WC()->cart->is_empty() ) {
            if ( function_exists( 'is_checkout' ) && ! is_checkout() ) {
                // Chỉ enqueue khi không phải trang checkout (vì đang nhúng vào wizard)
                wp_enqueue_script( 'wc-checkout' );
            }
        }
    }

    public function redirect_cart_page() {
        if( is_cart() ) { wp_redirect( home_url() ); exit; }
    }

    public function custom_checkout_script() {
        if( is_checkout() && !is_wc_endpoint_url('order-received') ) {
            ?>
            <script type="text/javascript">
            jQuery(document).ready(function($){
                setTimeout(function(){
                    var btn = $('.wc-block-components-checkout-return-to-cart-button');
                    if(btn.length > 0) {
                        btn.text('← Change Selection'); 
                        btn.attr('href', 'javascript:history.back()'); 
                    }
                }, 1000);
            });
            </script>
            <?php
        }
    }

    public function render_modals_in_footer() {
        $terms_txt = wpautop(get_option('visa_terms_content', ''));
        $privacy_txt = wpautop(get_option('visa_privacy_content', ''));
        $refund_txt = wpautop(get_option('visa_refund_content', ''));
        ?>
        <div id="modal_terms" class="visa-modal" style="display:none;"><div class="visa-modal-content"><span class="visa-close">&times;</span><div class="visa-modal-body"><?php echo $terms_txt; ?></div></div></div>
        <div id="modal_privacy" class="visa-modal" style="display:none;"><div class="visa-modal-content"><span class="visa-close">&times;</span><div class="visa-modal-body"><?php echo $privacy_txt; ?></div></div></div>
        <div id="modal_refund" class="visa-modal" style="display:none;"><div class="visa-modal-content"><span class="visa-close">&times;</span><div class="visa-modal-body"><?php echo $refund_txt; ?></div></div></div>
        <?php
    }

    /* ================= 3. RENDER WIZARD (SHORTCODE) ================= */

    public function render_wizard( $atts ) {
        $atts = shortcode_atts( ['product_id' => 0], $atts );
        $pid = intval( $atts['product_id'] );
        $product = wc_get_product( $pid );

        if ( ! $product || ! $product->is_type( 'variable' ) ) return '<p style="color:red; font-weight:bold;">ERROR: Invalid Product ID.</p>';

        $attributes = $product->get_variation_attributes();
        $attr_keys = array_keys( $attributes );
        $slug_type = isset($attr_keys[0]) ? $attr_keys[0] : '';
        $slug_time = isset($attr_keys[1]) ? $attr_keys[1] : '';

        // PREFILL & SESSION
        $prefill = [];
        if ( WC()->session && WC()->session->get('visa_draft_data') ) {
            $prefill = WC()->session->get('visa_draft_data');
        }
        if ( ! WC()->cart->is_empty() ) { WC()->cart->empty_cart(); }

        $nationalities_str = get_option( 'visa_nationalities_list', '' );
        $nationalities = !empty($nationalities_str) ? preg_split("/\r\n|\n|\r/", $nationalities_str) : ['Vietnam', 'USA', 'UK'];
        $nationalities = array_filter(array_map('trim', $nationalities));

        $work_days = get_option('visa_work_days', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
        $work_start = get_option('visa_work_start', '08:30');
        $work_end = get_option('visa_work_end', '16:30');
        $work_days_str = (count($work_days) == 5 && in_array('Mon',$work_days) && in_array('Fri',$work_days)) ? "Mon-Fri" : implode(', ', (array)$work_days);
        $schedule_note = get_option('visa_schedule_note', 'Show info: Processing time counts from the time the application is confirmed, not submitted, during working hours from {time_from} to {time_to} Vietnam Local Time from {date_from} to {date_to}, except Public Holidays');
        $pricing_notes = get_option('visa_pricing_notes', '');
        $date_from = get_option('visa_date_from', '');
        $date_to = get_option('visa_date_to', '');
        $terms_checkbox_text = get_option('visa_terms_checkbox_text', 'Click to agree: By submitting payment, I acknowledge that I have read and accept the EVISAS VIETNAM Terms of Service, Privacy Policy, and Refund Policy.');
        
        // Replace placeholders in schedule_note
        $schedule_note_display = str_replace(
            array('{time_from}', '{time_to}', '{date_from}', '{date_to}'),
            array($work_start, $work_end, $date_from ? date('d/m/Y', strtotime($date_from)) : '', $date_to ? date('d/m/Y', strtotime($date_to)) : ''),
            $schedule_note
        );

        $phone_codes = $this->get_all_phone_codes();
        
        ob_start();
        ?>
        <div class="visa-wizard-container contact-section" id="visa_wizard">
            <!-- Progress: thin bar on top only -->
            <div class="visa-progress-top">
                <div class="visa-progress-track"><div class="visa-progress-fill" id="progress_bar"></div></div>
            </div>
            <div class="form-inner">
                <div class="visa-sticky-header">
                    <div class="visa-step-info">Step <span id="current_step_num">1</span> of 7</div>
                    <div class="visa-total-price" id="header_price_display">--</div>
                </div>
                <div id="global_error" class="error-message">Please fill in all required fields.</div>

                <form id="visa_form">
                    <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                    <input type="hidden" name="variation_id" id="variation_id">
                    
                    <div class="step-content active" data-step="1">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">1</span>Nationality</h3>
                            <p class="visa-step-desc">Choose your nationality as shown on your passport to begin the application.</p>
                            <div class="form-group">
                                <select name="nationality" class="form-control required-field select2-enable" data-placeholder="Select your nationality">
                                    <option value="">Select your nationality</option>
                                    <?php foreach($nationalities as $n): ?>
                                        <option value="<?php echo esc_attr($n); ?>" <?php selected($prefill['nationality'] ?? '', $n); ?>><?php echo esc_html($n); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php
                    $type_slugs = isset( $attributes[ $slug_type ] ) ? $this->get_ordered_attribute_term_slugs( $slug_type, $attributes[ $slug_type ] ) : [];
                    if ( empty( $type_slugs ) && isset( $attributes[ $slug_type ] ) ) {
                        $type_slugs = array_values( $attributes[ $slug_type ] );
                    }
                    $time_slugs = isset( $attributes[ $slug_time ] ) ? $this->get_ordered_attribute_term_slugs( $slug_time, $attributes[ $slug_time ] ) : [];
                    if ( empty( $time_slugs ) && isset( $attributes[ $slug_time ] ) ) {
                        $time_slugs = array_values( $attributes[ $slug_time ] );
                    }
                    ?>
                    <div class="step-content" data-step="2">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">2</span>Visa Type</h3>
                            <p class="visa-step-desc">Select the type of entry and validity duration suitable for your trip.</p>
                            <div class="form-group">
                                <select name="visa_type" class="form-control price-trigger required-field select2-enable" id="select_visa_type" data-placeholder="Select visa type">
                                    <option value="">Select visa type</option>
                                    <?php foreach ( $type_slugs as $term_slug ):
                                        $term_label = $this->get_attribute_label( $term_slug, $slug_type ); ?>
                                        <option value="<?php echo esc_attr( $term_slug ); ?>" data-label="<?php echo esc_attr( $term_label ); ?>"><?php echo esc_html( $term_label ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="3">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">3</span>Processing Time</h3>
                            <p class="visa-step-desc">Choose how quickly you need your visa processed based on your urgency.</p>
                            <div class="form-group">
                                <select name="processing_time" class="form-control price-trigger required-field select2-enable" id="select_processing_time" data-placeholder="Select processing time">
                                    <option value="">Select processing time</option>
                                    <?php foreach ( $time_slugs as $term_slug ):
                                        $term_label = $this->get_attribute_label( $term_slug, $slug_time ); ?>
                                        <option value="<?php echo esc_attr( $term_slug ); ?>" data-label="<?php echo esc_attr( $term_label ); ?>"><?php echo esc_html( $term_label ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if ( ! empty( $schedule_note_display ) ): ?>
                                <p class="visa-step-note"><?php echo esc_html( $schedule_note_display ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $pricing_notes ) ): ?>
                                <p class="visa-step-note"><?php echo wp_kses_post( nl2br( $pricing_notes ) ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="step-content" data-step="4">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">4</span>Arrival Date</h3>
                            <p class="visa-step-desc">Specify your expected arrival date in Vietnam to determine visa validity start.</p>
                            <div class="form-group">
                                <input type="date" name="arrival_date" class="form-control required-field" value="<?php echo esc_attr($prefill['arrival_date'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="5">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">5</span>Number of Travelers</h3>
                            <p class="visa-step-desc">Enter the number of travelers for this visa application.</p>
                            <div class="form-group">
                                <input type="number" name="number_of_travelers" id="number_of_travelers" class="form-control required-field" min="1" max="10" value="<?php echo esc_attr($prefill['number_of_travelers'] ?? '1'); ?>" placeholder="Number of travelers (1-10)">
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="6">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">6</span>Upload Documents</h3>
                            <p class="visa-step-desc">Upload clear photos of your passport data page and a recent portrait for each traveler.</p>
                            <div id="travelers_upload_container">
                                <!-- Upload fields sẽ được generate động bằng JavaScript dựa trên số người -->
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="7">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">7</span>Contact Information</h3>
                            <p class="visa-step-desc">Provide contact details for each traveler.</p>
                            <div id="travelers_contact_container">
                                <!-- Contact fields sẽ được generate động bằng JavaScript dựa trên số người -->
                            </div>
                            <div class="form-group visa-terms-wrap">
                                <label class="visa-terms-label">
                                    <input type="checkbox" id="agree_terms" class="required-field">
                                    <span id="terms_checkbox_text"><?php echo esc_html( $terms_checkbox_text ); ?></span>
                                </label>
                                <div class="visa-terms-scroll-box" id="visa_terms_scroll" style="display:none; max-height:200px; overflow-y:auto; margin-top:12px; padding:12px; border:1px solid #ddd; border-radius:4px; background:#f9f9f9;">
                                    <div id="terms_content_display"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="visa-actions">
                        <button type="button" class="btn-1 btn-back" id="btn_back" style="display:none;">← Back</button>
                        <span class="visa-btn-spacer"></span>
                        <button type="button" class="btn-1 btn-next" id="btn_next">Next →</button>
                        <button type="button" class="btn-1 btn-checkout" id="btn_submit" style="display:none;">PAY NOW</button>
                    </div>
                </form>

                <!-- Step 8 ĐẶT NGOÀI form để tránh form lồng form (checkout form có thẻ <form> riêng) -->
                <div class="step-content" data-step="8">
                    <div class="visa-step-inner">
                        <h3 class="step-title"><span class="visa-step-badge">8</span>Review</h3>
                        <p class="visa-step-desc">Review your application details and proceed to secure payment to finalize.</p>
                        
                        <div class="review-box" id="review_summary">
                            <div class="review-item"><span>Nationality:</span> <span class="review-value" id="rev_nation">--</span></div>
                            <div class="review-item"><span>Visa Type:</span> <span class="review-value" id="rev_type">--</span></div>
                            <div class="review-item"><span>Processing Time:</span> <span class="review-value" id="rev_time">--</span></div>
                            <div class="review-item"><span>Arrival Date:</span> <span class="review-value" id="rev_date">--</span></div>
                            <div class="review-item"><span>Number of Travelers:</span> <span class="review-value" id="rev_travelers">--</span></div>
                            <div class="review-item"><span>Travelers Info:</span> <span class="review-value" id="rev_name" style="text-align:left; display:block; margin-top:8px;"></span></div>
                            <div class="review-item review-total">
                                <span>Total:</span> <span class="review-value" id="rev_price">--</span>
                            </div>
                        </div>
                        
                        <div id="visa_checkout_wrapper" class="visa-checkout-wrapper">
                            <!-- Checkout form sẽ được load vào đây -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($){
            let currentStep = 1; const totalSteps = 8;
            $(".select2-enable").each(function(){
                var ph = $(this).data("placeholder");
                $(this).select2({ width: "100%", placeholder: ph || "Select..." });
            });

            // Restore Values (Prefill)
            <?php if(!empty($prefill['visa_type'])): ?>
                $("select[name='visa_type']").val("<?php echo esc_js($prefill['visa_type']); ?>").trigger("change");
            <?php endif; ?>
            <?php if(!empty($prefill['processing_time'])): ?>
                $("select[name='processing_time']").val("<?php echo esc_js($prefill['processing_time']); ?>").trigger("change");
            <?php endif; ?>
            <?php if(!empty($prefill['phone_code'])): ?>
                $("select[name='phone_code']").val("<?php echo esc_js($prefill['phone_code']); ?>").trigger("change");
            <?php endif; ?>

            // FIX: AUTO TRIGGER PRICE CALCULATION AFTER PREFILL
            setTimeout(function(){
                let pType = $("select[name='visa_type']").val();
                let pTime = $("select[name='processing_time']").val();
                if(pType && pTime) {
                    $(".price-trigger").first().trigger("change");
                }
            }, 500); // Đợi 500ms để chắc chắn Select2 đã load xong

            // Modal
            $(document).on("click", ".visa-link", function(e){
                e.preventDefault(); e.stopPropagation();
                let target = "#" + $(this).data("target");
                $(target).css("display", "flex").hide().fadeIn();
            });
            $(document).on("click", ".visa-close, .visa-modal", function(e){
                if(e.target === this || $(this).hasClass("visa-close")) $(".visa-modal").fadeOut();
            });

            // Generate upload và contact fields dựa trên số người
            function generateTravelersUpload(num) {
                num = parseInt(num) || 1;
                if(num < 1) num = 1;
                if(num > 10) num = 10;
                var html = '';
                for(var i = 1; i <= num; i++) {
                    html += '<div class="traveler-upload-section" data-traveler="' + i + '">';
                    html += '<h4 style="margin:20px 0 12px; font-size:16px; color:#222;">Traveler ' + i + '</h4>';
                    html += '<div class="visa-upload-grid">';
                    html += '<div class="form-group">';
                    html += '<label style="display:block; margin-bottom:8px; font-weight:600; color:#555;">Passport</label>';
                    html += '<div class="file-upload-wrapper">';
                    html += '<input type="file" id="file_passport_' + i + '" accept="image/*" class="form-control traveler-upload-file" data-traveler="' + i + '" data-type="passport">';
                    html += '<input type="hidden" name="passport_url_' + i + '" id="passport_url_' + i + '" class="required-field traveler-passport-url">';
                    html += '</div>';
                    html += '<div id="stat_passport_' + i + '" class="upload-status"></div>';
                    html += '<div class="upload-preview-box" id="prev_passport_' + i + '"></div>';
                    html += '</div>';
                    html += '<div class="form-group">';
                    html += '<label style="display:block; margin-bottom:8px; font-weight:600; color:#555;">Photo</label>';
                    html += '<div class="file-upload-wrapper">';
                    html += '<input type="file" id="file_photo_' + i + '" accept="image/*" class="form-control traveler-upload-file" data-traveler="' + i + '" data-type="photo">';
                    html += '<input type="hidden" name="photo_url_' + i + '" id="photo_url_' + i + '" class="required-field traveler-photo-url">';
                    html += '</div>';
                    html += '<div id="stat_photo_' + i + '" class="upload-status"></div>';
                    html += '<div class="upload-preview-box" id="prev_photo_' + i + '"></div>';
                    html += '</div>';
                    html += '</div></div>';
                }
                $("#travelers_upload_container").html(html);
                // Bind upload handlers cho tất cả fields mới
                $(".traveler-upload-file").off("change").on("change", function(){
                    var traveler = $(this).data("traveler");
                    var type = $(this).data("type");
                    var fileInput = this;
                    var fd = new FormData();
                    fd.append("file", fileInput.files[0]);
                    fd.append("action", "visa_upload_file");
                    var msgId = "#stat_" + type + "_" + traveler;
                    var hiddenId = "#" + type + "_url_" + traveler;
                    var prevId = "#prev_" + type + "_" + traveler;
                    $(msgId).text("Uploading...").css("color","#ffaa17");
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "POST",
                        contentType: false,
                        processData: false,
                        data: fd,
                        success: function(res){
                            if(res.success){
                                $(hiddenId).val(res.data.url);
                                $(msgId).text("Success").css("color","green");
                                $(prevId).html("<img src=\""+res.data.url+"\">").fadeIn().show();
                                $(fileInput).closest(".file-upload-wrapper").find("input[type='file']").css("border-color", "green");
                            } else {
                                $(msgId).text("Error").css("color","red");
                            }
                        }
                    });
                });
            }

            function generateTravelersContact(num) {
                num = parseInt(num) || 1;
                if(num < 1) num = 1;
                if(num > 10) num = 10;
                var html = '';
                var phoneCodes = <?php echo json_encode($phone_codes); ?>;
                for(var i = 1; i <= num; i++) {
                    html += '<div class="traveler-contact-section" data-traveler="' + i + '">';
                    html += '<h4 style="margin:20px 0 12px; font-size:16px; color:#222;">Traveler ' + i + '</h4>';
                    html += '<div class="form-group">';
                    html += '<input type="text" name="contact_name_' + i + '" class="form-control required-field" placeholder="Contact Name" value="">';
                    html += '</div>';
                    html += '<div class="form-group">';
                    html += '<input type="email" name="email_' + i + '" class="form-control required-field" placeholder="Email address" value="">';
                    html += '</div>';
                    html += '<div class="form-group phone-group">';
                    html += '<div class="phone-code-wrap">';
                    html += '<select name="phone_code_' + i + '" class="form-control select2-enable traveler-phone-code">';
                    for(var code in phoneCodes) {
                        html += '<option value="' + code + '">' + phoneCodes[code] + '</option>';
                    }
                    html += '</select>';
                    html += '</div>';
                    html += '<div class="phone-number-wrap">';
                    html += '<input type="tel" name="phone_number_' + i + '" class="form-control required-field traveler-phone-number" placeholder="Phone number" value="">';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                $("#travelers_contact_container").html(html);
                // Re-init Select2 cho phone codes
                $(".traveler-phone-code").select2({ width: "100%", placeholder: "Select..." });
            }

            // Khi số người thay đổi, generate lại fields
            $(document).on("change", "#number_of_travelers", function(){
                var num = parseInt($(this).val()) || 1;
                if(num < 1) {
                    $(this).val(1);
                    num = 1;
                }
                if(num > 10) {
                    $(this).val(10);
                    num = 10;
                }
                generateTravelersUpload(num);
                generateTravelersContact(num);
            });

            // Validate number of travelers
            function validateNumberOfTravelers() {
                var num = parseInt($("#number_of_travelers").val()) || 0;
                if(num < 1 || num > 10) {
                    $("#number_of_travelers").addClass("input-error");
                    return false;
                }
                $("#number_of_travelers").removeClass("input-error");
                return true;
            }

            // Terms checkbox với scroll box - phân tách rõ từng mục
            var termsContent = <?php echo json_encode( wpautop( get_option('visa_terms_content', '') ) ); ?>;
            var privacyContent = <?php echo json_encode( wpautop( get_option('visa_privacy_content', '') ) ); ?>;
            var refundContent = <?php echo json_encode( wpautop( get_option('visa_refund_content', '') ) ); ?>;
            
            var scrollBoxHtml = '';
            if(termsContent && termsContent.trim()) {
                scrollBoxHtml += '<div class="terms-section-item" style="margin-bottom:24px; padding-bottom:20px; border-bottom:2px solid #ddd;">';
                scrollBoxHtml += '<h4 style="margin:0 0 12px 0; font-size:16px; font-weight:700; color:#222;">Terms of Service</h4>';
                scrollBoxHtml += '<div style="font-size:13px; line-height:1.6; color:#555;">' + termsContent + '</div>';
                scrollBoxHtml += '</div>';
            }
            if(privacyContent && privacyContent.trim()) {
                scrollBoxHtml += '<div class="terms-section-item" style="margin-bottom:24px; padding-bottom:20px; border-bottom:2px solid #ddd;">';
                scrollBoxHtml += '<h4 style="margin:0 0 12px 0; font-size:16px; font-weight:700; color:#222;">Privacy Policy</h4>';
                scrollBoxHtml += '<div style="font-size:13px; line-height:1.6; color:#555;">' + privacyContent + '</div>';
                scrollBoxHtml += '</div>';
            }
            if(refundContent && refundContent.trim()) {
                scrollBoxHtml += '<div class="terms-section-item" style="margin-bottom:0; padding-bottom:0; border-bottom:none;">';
                scrollBoxHtml += '<h4 style="margin:0 0 12px 0; font-size:16px; font-weight:700; color:#222;">Refund Policy</h4>';
                scrollBoxHtml += '<div style="font-size:13px; line-height:1.6; color:#555;">' + refundContent + '</div>';
                scrollBoxHtml += '</div>';
            }
            $("#terms_content_display").html(scrollBoxHtml);
            
            // Replace text với links từ visa-terms-label
            var termsText = $("#terms_checkbox_text").html();
            termsText = termsText.replace(/Terms of Service/gi, '<span class="visa-link" data-target="modal_terms">Terms of Service</span>');
            termsText = termsText.replace(/Privacy Policy/gi, '<span class="visa-link" data-target="modal_privacy">Privacy Policy</span>');
            termsText = termsText.replace(/Refund Policy/gi, '<span class="visa-link" data-target="modal_refund">Refund Policy</span>');
            $("#terms_checkbox_text").html(termsText);
            
            $(document).on("change", "#agree_terms", function(){
                if($(this).is(":checked")) {
                    $("#visa_terms_scroll").slideDown();
                } else {
                    $("#visa_terms_scroll").slideUp();
                }
            });

            // Khởi tạo với số người mặc định
            var defaultTravelers = parseInt($("#number_of_travelers").val()) || 1;
            generateTravelersUpload(defaultTravelers);
            generateTravelersContact(defaultTravelers);

            showStep(1);

            function showStep(step) {
                $("#global_error").hide();
                $(".step-content").removeClass("active").hide();
                $(".step-content[data-step=\""+step+"\"]").fadeIn(300).addClass("active");
                $("#current_step_num").text(step);
                $("#progress_bar").css("width", (step/totalSteps)*100 + "%");
                if(step === 1) $("#btn_back").hide(); else $("#btn_back").show();
                if(step === totalSteps) { 
                    $("#btn_next").hide(); 
                    $("#btn_submit").hide(); 
                    populateReview();
                    if(!$("#visa_checkout_wrapper").html().trim()) {
                        loadCheckoutForm();
                    }
                } else { 
                    $("#btn_next").show().prop("disabled", false).text("Next →"); 
                    $("#btn_submit").hide(); 
                }
            }
            
            function loadCheckoutForm() {
                var $wrap = $("#visa_checkout_wrapper");
                $wrap.addClass("visa-checkout-loading").removeClass("visa-checkout-loaded");
                $wrap.html('<div style="text-align:center;padding:20px;">Loading checkout form...</div>');
                $.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                    action: "visa_load_checkout"
                }, function(res){
                    if(res.success) {
                        $wrap.html(res.data.html);
                        setTimeout(function() {
                            var $checkoutForm = $("#visa_checkout_form, #visa_checkout_wrapper form.checkout, #visa_checkout_wrapper form[name='checkout'], #visa_checkout_wrapper form");
                            if($checkoutForm.length > 0) {
                                $checkoutForm.attr("action", "javascript:void(0);");
                                $checkoutForm.attr("onsubmit", "return false;");
                                bindCheckoutFormSubmit();
                                if(typeof jQuery !== 'undefined') {
                                    jQuery('body').trigger('update_checkout');
                                    if(typeof wc_checkout_params === 'undefined') {
                                        var script = document.createElement('script');
                                        script.src = '<?php echo WC()->plugin_url(); ?>/assets/js/frontend/checkout.js';
                                        script.onload = function() { jQuery('body').trigger('update_checkout'); };
                                        document.head.appendChild(script);
                                    }
                                }
                            } else {
                                setTimeout(function() { bindCheckoutFormSubmit(); }, 300);
                            }
                            $wrap.removeClass("visa-checkout-loading").addClass("visa-checkout-loaded");
                        }, 200);
                    } else {
                        $wrap.removeClass("visa-checkout-loading").addClass("visa-checkout-loaded");
                        $wrap.html('<div style="color:red;text-align:center;padding:20px;">Error loading checkout form. Please refresh the page.</div>');
                    }
                }).fail(function() {
                    $wrap.removeClass("visa-checkout-loading").addClass("visa-checkout-loaded");
                    $wrap.html('<div style="color:red;text-align:center;padding:20px;">Error loading checkout form. Please refresh the page.</div>');
                });
            }
            
            function bindCheckoutFormSubmit() {
                var $form = $("#visa_checkout_form, #visa_checkout_wrapper form.checkout, #visa_checkout_wrapper form[name='checkout'], #visa_checkout_wrapper form");
                
                if($form.length === 0) return false;
                
                $form.off("submit.visaCheckout");
                $form.attr("action", "javascript:void(0);");
                $form.attr("onsubmit", "return false;");
                
                $form.on("submit.visaCheckout", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    let $form = $(this);
                    
                    let $submitBtn = $form.find("#place_order");
                    if($submitBtn.length === 0) {
                        console.error("Place order button not found!");
                        return false;
                    }
                    let originalText = $submitBtn.val() || $submitBtn.text();
                    
                    // Disable button và show loading
                    $submitBtn.prop("disabled", true);
                    if($submitBtn.is("button")) {
                        $submitBtn.text("Processing...");
                    } else {
                        $submitBtn.val("Processing...");
                    }
                    
                    // Xóa các error message cũ
                    $form.find(".woocommerce-error").remove();
                    
                    // Lấy tất cả form data
                    let formData = $form.serialize();
                    
                    // Đảm bảo có woocommerce_checkout_place_order
                    if(formData.indexOf("woocommerce_checkout_place_order") === -1) {
                        formData += "&woocommerce_checkout_place_order=1";
                    }
                    
                    // Sử dụng AJAX endpoint riêng để xử lý checkout
                    var checkoutUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
                    // Thêm action để xử lý checkout
                    formData += "&action=visa_process_checkout";
                    
                    console.log("Sending AJAX to:", checkoutUrl);
                    console.log("Form data:", formData);
                    
                    $.ajax({
                        url: checkoutUrl,
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        timeout: 30000,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            // console.log("Checkout response:", response);
                            var redirectUrl = null;
                            // WordPress format: { success: true, data: { redirect: "..." } }
                            if (response && response.success && response.data && response.data.redirect) {
                                redirectUrl = response.data.redirect;
                            }
                            // WooCommerce format: { result: "success", redirect: "...", order_id: ... }
                            if (!redirectUrl && response && response.result === 'success' && response.redirect) {
                                redirectUrl = response.redirect;
                            }
                            if (redirectUrl) {
                                clearVisaForm();
                                window.location.href = redirectUrl;
                                return;
                            }
                            // Hiển thị lỗi
                            var errorMsg = "Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.";
                            if (response && response.data && response.data.messages) {
                                errorMsg = response.data.messages;
                                $form.prepend('<div class="woocommerce-error" role="alert">' + errorMsg + '</div>');
                            } else if (response && response.data && response.data.message) {
                                errorMsg = response.data.message;
                                $form.prepend('<div class="woocommerce-error" role="alert">' + errorMsg + '</div>');
                            } else if (response && response.messages) {
                                errorMsg = response.messages;
                                $form.prepend('<div class="woocommerce-error" role="alert">' + errorMsg + '</div>');
                            } else {
                                alert(errorMsg);
                            }
                            $submitBtn.prop("disabled", false);
                            if ($submitBtn.is("button")) {
                                $submitBtn.text(originalText);
                            } else {
                                $submitBtn.val(originalText);
                            }
                        },
                        error: function(xhr, status, error) {
                            var responseText = xhr.responseText || "";
                            try {
                                var jsonResponse = JSON.parse(responseText);
                                if (jsonResponse && jsonResponse.result === 'success' && jsonResponse.redirect) {
                                    clearVisaForm();
                                    window.location.href = jsonResponse.redirect;
                                    return;
                                }
                                if (jsonResponse && jsonResponse.success && jsonResponse.data && jsonResponse.data.redirect) {
                                    clearVisaForm();
                                    window.location.href = jsonResponse.data.redirect;
                                    return;
                                }
                                if (jsonResponse && (jsonResponse.success === false || jsonResponse.result === 'failure')) {
                                    var errorMsg = (jsonResponse.data && jsonResponse.data.messages) || jsonResponse.messages || "Có lỗi xảy ra khi xử lý đơn hàng.";
                                    $form.prepend('<div class="woocommerce-error" role="alert">' + errorMsg + '</div>');
                                    $submitBtn.prop("disabled", false);
                                    if($submitBtn.is("button")) $submitBtn.text(originalText); else $submitBtn.val(originalText);
                                    return;
                                }
                            } catch(e) {}
                            var redirectMatch = responseText.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
                            if(redirectMatch && redirectMatch[1]) {
                                clearVisaForm();
                                window.location.href = redirectMatch[1];
                                return;
                            }
                            alert("Có lỗi xảy ra khi xử lý đơn hàng: " + (error || status));
                            $submitBtn.prop("disabled", false);
                            if($submitBtn.is("button")) {
                                $submitBtn.text(originalText);
                            } else {
                                $submitBtn.val(originalText);
                            }
                        }
                    });
                    
                    return false;
                });
            }

            function validateStep(step) {
                let isValid = true;
                let currentPanel = $(".step-content[data-step=\""+step+"\"]");
                
                // Validate step 5: Number of Travelers
                if(step === 5) {
                    if(!validateNumberOfTravelers()) {
                        isValid = false;
                        $("#global_error").slideDown();
                        return false;
                    }
                }
                
                currentPanel.find(".required-field").filter(":input, select").each(function(){
                    // Kiểm tra checkbox
                    if($(this).is(":checkbox")) {
                        if(!$(this).is(":checked")) {
                            isValid = false; 
                            $(this).closest("label").addClass("input-error");
                        } else {
                            $(this).closest("label").removeClass("input-error");
                        }
                        return;
                    }
                    
                    if(!$(this).val() || $(this).val() === "") {
                        isValid = false; $(this).addClass("input-error");
                        if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").addClass("input-error"); }
                    } else { 
                        // Validate phone number format (step 7 - Contact Information)
                        if($(this).hasClass("traveler-phone-number") && step === 7) {
                            if(!validatePhoneNumber($(this).val())) {
                                isValid = false;
                                $(this).addClass("input-error");
                                return;
                            }
                        }
                        $(this).removeClass("input-error"); 
                        if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").removeClass("input-error"); }
                    }
                });
                if(!isValid) $("#global_error").slideDown();
                return isValid;
            }

            $(document).on("change", ".required-field", function() {
                // Xử lý checkbox
                if($(this).is(":checkbox")) {
                    if($(this).is(":checked")) {
                        $(this).closest("label").removeClass("input-error");
                        $("#global_error").hide();
                    }
                    return;
                }
                
                if($(this).val()) { 
                    $(this).removeClass("input-error"); 
                    if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").removeClass("input-error"); }
                    $("#global_error").hide(); 
                }
            });

            // Phone number validation: chỉ cho phép số (cho tất cả phone number fields)
            $(document).on("input", "input.traveler-phone-number, input[name='phone_number']", function() {
                let value = $(this).val();
                // Chỉ giữ lại số (0-9)
                let cleaned = value.replace(/[^0-9]/g, '');
                if(value !== cleaned) {
                    $(this).val(cleaned);
                }
            });

            // Validate phone number format khi submit
            function validatePhoneNumber(phone) {
                // Chỉ cho phép số, tối thiểu 7 chữ số, tối đa 15 chữ số (theo ITU-T E.164)
                return /^[0-9]{7,15}$/.test(phone);
            }

            $("#btn_next").click(function(e){ 
                e.preventDefault(); 
                if(validateStep(currentStep)) { 
                    // Nếu đang ở step 7, cần thêm vào cart trước khi chuyển sang step 8
                    if(currentStep === 7) {
                        // Kiểm tra terms checkbox
                        if(!$("#agree_terms").is(":checked")) { 
                            alert("Please accept terms."); 
                            return; 
                        }
                        
                        if(!$("#variation_id").val()) {
                            alert("Please re-select Visa Type or Processing Time (Price not calculated).");
                            return;
                        }
                        
                        let btn = $(this); 
                        btn.text("Processing...").prop("disabled", true);
                        $.post("<?php echo admin_url('admin-ajax.php'); ?>", { 
                            action: "visa_checkout", 
                            data: $("#visa_form").serialize() 
                        }, function(res){
                            if(res.success) {
                                // Thêm vào cart thành công, chuyển sang step 8 và load checkout
                                currentStep++; 
                                showStep(currentStep);
                                loadCheckoutForm();
                            } else { 
                                alert(res.data.message); 
                                btn.text("Next →").prop("disabled", false); 
                            }
                        });
                    } else {
                        currentStep++; 
                        showStep(currentStep);
                    }
                } 
            });
            $("#btn_back").click(function(e){ e.preventDefault(); currentStep--; showStep(currentStep); });

            // Hàm tính và hiển thị giá (nhân với số người)
            function calculateAndDisplayPrice() {
                let type = $("select[name=\"visa_type\"]").val();
                let time = $("select[name=\"processing_time\"]").val();
                let numTravelers = parseInt($("#number_of_travelers").val()) || 1;
                
                if(type && time) {
                    $("#header_price_display").css("opacity", "0.5");
                    $.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                        action: "visa_get_price", 
                        product_id: $("input[name=\"product_id\"]").val(), 
                        type: type, 
                        time: time
                    }, function(res){
                        $("#header_price_display").css("opacity", "1");
                        if(res.success) { 
                            // Lưu variation_id
                            $("#variation_id").val(res.data.variation_id);
                            
                            // Tính giá tổng = giá gốc * số người
                            if(res.data.raw_price) {
                                let basePrice = parseFloat(res.data.raw_price);
                                let totalPrice = basePrice * numTravelers;
                                let currency = res.data.currency || "$";
                                
                                // Format số với dấu phẩy ngăn cách hàng nghìn
                                let formattedPrice = totalPrice.toFixed(2);
                                formattedPrice = formattedPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                
                                // Hiển thị giá
                                if(numTravelers > 1) {
                                    $("#header_price_display").html(currency + formattedPrice + " <small style='color:#888; font-size:0.85em;'>(<?php echo esc_js(__('for', 'woocommerce')); ?> " + numTravelers + " <?php echo esc_js(__('traveler(s)', 'woocommerce')); ?>)</small>");
                                } else {
                                    $("#header_price_display").html(currency + formattedPrice);
                                }
                            } else {
                                // Fallback: dùng price_html nếu không có raw_price
                                $("#header_price_display").html(res.data.price_html);
                            }
                        } else { 
                            $("#header_price_display").text("--"); 
                            $("#variation_id").val("");
                        }
                    });
                }
            }

            // SMART PRICE CALCULATION
            $(".price-trigger").change(function(){
                calculateAndDisplayPrice();
            });
            
            // Khi số người thay đổi, tính lại giá
            $(document).on("change", "#number_of_travelers", function(){
                calculateAndDisplayPrice();
            });

            // Upload handlers đã được bind trong generateTravelersUpload()

            function populateReview() {
                // Nationality
                let nationality = $("select[name=\"nationality\"]").val() || "--";
                $("#rev_nation").text(nationality);
                
                // Visa Type
                let typeLabel = $("#select_visa_type option:selected").data("label");
                if(!typeLabel) {
                    typeLabel = $("#select_visa_type option:selected").text();
                }
                $("#rev_type").text(typeLabel || "--");
                
                // Processing Time
                let timeLabel = $("#select_processing_time option:selected").data("label");
                if(!timeLabel) {
                    timeLabel = $("#select_processing_time option:selected").text();
                }
                $("#rev_time").text(timeLabel || "--");
                
                // Arrival Date
                let arrivalDate = $("input[name=\"arrival_date\"]").val() || "--";
                $("#rev_date").text(arrivalDate);
                
                // Number of Travelers
                let numTravelers = parseInt($("#number_of_travelers").val()) || 1;
                $("#rev_travelers").text(numTravelers);
                
                // Travelers info
                let travelersHtml = "";
                for(var i = 1; i <= numTravelers; i++) {
                    let contactName = $("input[name=\"contact_name_" + i + "\"]").val() || "--";
                    let email = $("input[name=\"email_" + i + "\"]").val() || "--";
                    let phoneCode = $("select[name=\"phone_code_" + i + "\"]").val() || "";
                    let phoneNumber = $("input[name=\"phone_number_" + i + "\"]").val() || "";
                    let phone = phoneCode && phoneNumber ? phoneCode + " " + phoneNumber : (phoneNumber || "--");
                    let passportUrl = $("input[name=\"passport_url_" + i + "\"]").val() || "";
                    let photoUrl = $("input[name=\"photo_url_" + i + "\"]").val() || "";
                    
                    travelersHtml += "<div style=\"margin-top:12px; padding-top:12px; border-top:1px solid #eee; font-size:13px; line-height:1.6;\">";
                    travelersHtml += "<strong style=\"color:#222;\">Traveler " + i + ":</strong><br>";
                    travelersHtml += "Contact Name: " + contactName + "<br>";
                    travelersHtml += "Email: " + email + "<br>";
                    travelersHtml += "Phone: " + phone + "<br>";
                    travelersHtml += "Passport: " + (passportUrl ? '<span style="color:green;">✓ Uploaded</span>' : "--") + "<br>";
                    travelersHtml += "Photo: " + (photoUrl ? '<span style="color:green;">✓ Uploaded</span>' : "--");
                    travelersHtml += "</div>";
                }
                
                // Update review display
                $("#rev_name").html(travelersHtml || "--");
                
                // Price
                let priceHtml = $("#header_price_display").html() || "--";
                $("#rev_price").html(priceHtml);
            }

            function clearVisaForm() {
                var $f = $("#visa_form");
                if ($f.length) $f[0].reset();
                $("#variation_id").val("");
                $("#number_of_travelers").val("1");
                $("#agree_terms").prop("checked", false);
                $("#visa_terms_scroll").hide();
                $("#rev_nation, #rev_type, #rev_time, #rev_date, #rev_name, #rev_email, #rev_phone, #rev_passport, #rev_photo").text("--");
                $("#rev_price").html("--");
                $(".traveler-passport-url, .traveler-photo-url").val("");
                $(".upload-preview-box").empty().hide();
                $(".upload-status").text("");
                generateTravelersUpload(1);
                generateTravelersContact(1);
                if ($(".select2-enable").length && typeof $().select2 === "function") {
                    $(".select2-enable").val(null).trigger("change");
                }
            }

            // Event handler sẽ được bind trong loadCheckoutForm() sau khi form được load
        });
        </script>
        <?php
        return ob_get_clean();
    }

    // --- BACKEND FUNCTIONS --- //
    public function ajax_get_price() {
        $pid = intval($_POST['product_id']);
        $selected_type = sanitize_text_field($_POST['type']);
        $selected_time = sanitize_text_field($_POST['time']);
        
        $product = wc_get_product($pid);
        if (!$product) { wp_send_json_error(['message' => 'Invalid Product']); }

        $variations = $product->get_available_variations();
        $matched_vid = 0;
        $price_html = '';
        $raw_price = 0;
        $currency = get_woocommerce_currency_symbol();

        foreach ($variations as $variation) {
            $is_match = true;
            $attributes = $variation['attributes'];

            foreach ($attributes as $attr_slug => $attr_value) {
                if ( empty($attr_value) ) continue;
                if ( $attr_value === $selected_type ) { continue; }
                if ( $attr_value === $selected_time ) { continue; }
                $is_match = false;
                break; 
            }

            if ($is_match) {
                $matched_vid = $variation['variation_id'];
                $price_html = $variation['price_html'];
                // Lấy giá số từ variation
                $variation_obj = wc_get_product($matched_vid);
                if ($variation_obj) {
                    $raw_price = floatval($variation_obj->get_price());
                }
                break;
            }
        }

        if ($matched_vid) {
            wp_send_json_success([
                'variation_id' => $matched_vid, 
                'price_html' => $price_html,
                'raw_price' => $raw_price,
                'currency' => $currency
            ]);
        } else {
            wp_send_json_error(['message' => 'No price found. Please check attributes.']);
        }
    }

    public function ajax_upload_file() {
        if(!function_exists('wp_handle_upload')) require_once(ABSPATH.'wp-admin/includes/file.php');
        $up = wp_handle_upload($_FILES['file'], ['test_form'=>false]);
        if(isset($up['url'])) wp_send_json_success(['url'=>$up['url']]); else wp_send_json_error();
    }

    public function ajax_checkout() {
        parse_str($_POST['data'], $form);
        
        // 1. SAVE DRAFT TO SESSION
        if ( WC()->session ) { WC()->session->set( 'visa_draft_data', $form ); }

        WC()->cart->empty_cart();
        
        // Thu thập data cho tất cả travelers
        $num_travelers = isset($form['number_of_travelers']) ? intval($form['number_of_travelers']) : 1;
        if($num_travelers < 1) $num_travelers = 1;
        if($num_travelers > 10) $num_travelers = 10;
        
        $travelers_data = [];
        for($i = 1; $i <= $num_travelers; $i++) {
            $phone_code = isset($form['phone_code_' . $i]) ? $form['phone_code_' . $i] : '';
            $phone_number = isset($form['phone_number_' . $i]) ? $form['phone_number_' . $i] : '';
            $full_phone = ($phone_code && $phone_number) ? $phone_code . ' ' . $phone_number : '';
            
            $travelers_data[] = [
                'contact_name' => isset($form['contact_name_' . $i]) ? $form['contact_name_' . $i] : '',
                'email' => isset($form['email_' . $i]) ? $form['email_' . $i] : '',
                'phone' => $full_phone,
                'phone_code' => $phone_code,
                'phone_number' => $phone_number,
                'passport' => isset($form['passport_url_' . $i]) ? $form['passport_url_' . $i] : '',
                'photo' => isset($form['photo_url_' . $i]) ? $form['photo_url_' . $i] : '',
            ];
        }
        
        // Lấy thông tin người đầu tiên cho billing
        $first_traveler = $travelers_data[0];
        $custom_data = [
            'visa_full_info' => [
                'nationality' => isset($form['nationality']) ? $form['nationality'] : '',
                'arrival' => isset($form['arrival_date']) ? $form['arrival_date'] : '',
                'number_of_travelers' => $num_travelers,
                'travelers' => $travelers_data,
                'contact_name' => $first_traveler['contact_name'],
                'email' => $first_traveler['email'],
                'phone' => $first_traveler['phone'],
                'phone_code' => $first_traveler['phone_code'],
                'phone_number' => $first_traveler['phone_number']
            ]
        ];
        
        if ( empty($form['variation_id']) ) {
            wp_send_json_error(['message' => 'Missing Price/Variation ID. Please re-select options.']);
        }

        // Thêm vào cart với số lượng = số người
        if(WC()->cart->add_to_cart( $form['product_id'], $num_travelers, $form['variation_id'], [], $custom_data )) {
            $c = WC()->customer;
            $c->set_billing_first_name($first_traveler['contact_name']);
            $c->set_billing_email($first_traveler['email']);
            $c->set_billing_phone($first_traveler['phone']);
            $c->set_billing_country('VN');
            $c->set_billing_address_1('Online App');
            $c->set_billing_city('Hanoi');
            $c->set_billing_postcode('');
            $c->save();
            wp_send_json_success(['message' => 'Added to cart successfully']);
        } else {
            wp_send_json_error(['message' => 'Error adding to cart. Please try again.']);
        }
    }
    
    public function ajax_load_checkout() {
        // Kiểm tra cart có sản phẩm không
        if ( WC()->cart->is_empty() ) {
            wp_send_json_error(['message' => 'Cart is empty']);
        }
        
        // Render checkout form
        ob_start();
        
        // Set checkout nonce
        if ( WC()->session ) {
            WC()->session->set( 'checkout', true );
        }
        
        $checkout = WC()->checkout();
        
        ob_start();
        echo '<div class="visa-checkout-form">';
        wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => $checkout ) );
        echo '</div>';
        $html = ob_get_clean();
        
        // Đổi form action thành javascript:void(0) để xử lý submit bằng AJAX, tránh redirect
        $html = preg_replace(
            '#<form([^>]*)\s+action="[^"]*"#',
            '<form$1 action="javascript:void(0);" onsubmit="return false;" id="visa_checkout_form"',
            $html,
            1
        );
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_process_checkout() {
        // Log ngay đầu để đảm bảo hàm được gọi
        $this->visa_log( '=== ajax_process_checkout CALLED ===' );
        $this->visa_log( 'POST keys: ' . implode( ', ', array_keys( $_POST ) ) );
        $this->visa_log( 'REQUEST action: ' . ( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'not set' ) );
        $this->visa_log( array(
            'action' => 'ajax_process_checkout_start',
            'time' => current_time( 'mysql' ),
            'cart_items' => function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
            'cart_total' => function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_total() : '',
        ) );
        
        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            $this->visa_log( 'ERROR: WooCommerce or cart not available' );
            wp_send_json_error( array( 'messages' => __( 'WooCommerce not ready.', 'woocommerce' ) ) );
        }
        
        if ( WC()->cart->is_empty() ) {
            $this->visa_log( 'ERROR: Cart is empty' );
            wp_send_json_error( array( 'messages' => __( 'Your cart is empty.', 'woocommerce' ) ) );
        }
        
        $nonce_value = isset( $_POST['woocommerce-process-checkout-nonce'] ) ? $_POST['woocommerce-process-checkout-nonce'] : '';
        if ( ! wp_verify_nonce( $nonce_value, 'woocommerce-process_checkout' ) ) {
            $this->visa_log( 'ERROR: Nonce verification failed' );
            wp_send_json_error( array( 'messages' => __( 'Security check failed. Please refresh the page and try again.', 'woocommerce' ) ) );
        }
        
        $payment_method = isset( $_POST['payment_method'] ) ? sanitize_text_field( $_POST['payment_method'] ) : '';
        if ( empty( $payment_method ) ) {
            $this->visa_log( 'ERROR: Payment method not selected. POST keys: ' . implode( ', ', array_keys( $_POST ) ) );
            wp_send_json_error( array( 'messages' => __( 'Please select a payment method.', 'woocommerce' ) ) );
        }
        
        $post_data_log = $_POST;
        unset( $post_data_log['woocommerce-process-checkout-nonce'] );
        $this->visa_log( 'POST data: ' . print_r( $post_data_log, true ) );
        
        $_POST['woocommerce_checkout_place_order'] = '1';
        $_REQUEST['wc-ajax'] = 'checkout';
        if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
            define( 'WOOCOMMERCE_CHECKOUT', true );
        }
        
        $redirect_url = null;
        $self = $this;
        add_filter( 'woocommerce_checkout_redirect', function( $url ) use ( &$redirect_url, $self ) {
            $redirect_url = $url;
            $self->visa_log( 'Redirect URL captured: ' . $url );
            return false;
        }, 999 );
        
        $order_id = null;
        add_action( 'woocommerce_checkout_order_processed', function( $id ) use ( &$order_id, $self ) {
            $order_id = $id;
            $self->visa_log( 'Order processed, ID: ' . $id );
        }, 10, 1 );
        
        $notices_before = wc_get_notices();
        if ( ! empty( $notices_before ) ) {
            $this->visa_log( 'Notices before process: ' . print_r( $notices_before, true ) );
        }
        
        ob_start();
        
        try {
            $checkout = WC()->checkout();
            $this->visa_log( 'Starting process_checkout()' );
            $checkout->process_checkout();
            $this->visa_log( 'process_checkout() completed' );
            
            $output = ob_get_clean();
            if ( ! empty( $output ) ) {
                $this->visa_log( 'Output buffer length: ' . strlen( $output ) );
                if ( strlen( $output ) < 800 ) {
                    $this->visa_log( 'Output: ' . $output );
                }
            }
            
            $notices = wc_get_notices( 'error' );
            $all_notices = wc_get_notices();
            $this->visa_log( 'All notices: ' . print_r( $all_notices, true ) );
            $this->visa_log( 'Error notices: ' . print_r( $notices, true ) );
            
            if ( ! empty( $notices ) ) {
                $error_messages = array();
                foreach ( $notices as $notice ) {
                    if ( is_array( $notice ) && isset( $notice['notice'] ) ) {
                        $error_messages[] = strip_tags( $notice['notice'] );
                    } elseif ( is_string( $notice ) ) {
                        $error_messages[] = strip_tags( $notice );
                    }
                }
                wc_clear_notices();
                $error_msg = ! empty( $error_messages ) ? implode( ' ', $error_messages ) : __( 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.', 'woocommerce' );
                $this->visa_log( 'WC errors (sending to user): ' . $error_msg );
                wp_send_json_error( array( 'messages' => $error_msg ) );
            }
            
            $this->visa_log( 'After process - redirect_url: ' . ( $redirect_url ? $redirect_url : 'null' ) . ', order_id: ' . ( $order_id ? $order_id : 'null' ) );
            
            if ( $redirect_url ) {
                $this->visa_log( 'SUCCESS redirect: ' . $redirect_url );
                $this->clear_visa_session();
                wp_send_json_success( array( 'redirect' => $redirect_url ) );
            }
            if ( $order_id ) {
                $order = wc_get_order( $order_id );
                if ( $order ) {
                    $redirect_url = $order->get_checkout_order_received_url();
                    $this->visa_log( 'SUCCESS order redirect: ' . $redirect_url );
                    $this->clear_visa_session();
                    wp_send_json_success( array( 'redirect' => $redirect_url ) );
                }
                $this->visa_log( 'ERROR: order_id exists but order object null' );
                wp_send_json_error( array( 'messages' => __( 'Order created but unable to get redirect URL.', 'woocommerce' ) ) );
            }
            
            $fallback_id = WC()->session->get( 'order_awaiting_payment' );
            $this->visa_log( 'Fallback order_awaiting_payment: ' . ( $fallback_id ? $fallback_id : 'null' ) );
            if ( $fallback_id ) {
                $order = wc_get_order( $fallback_id );
                if ( $order ) {
                    $redirect_url = $order->get_checkout_order_received_url();
                    $this->visa_log( 'SUCCESS fallback redirect: ' . $redirect_url );
                    $this->clear_visa_session();
                    wp_send_json_success( array( 'redirect' => $redirect_url ) );
                }
            }
            
            $this->visa_log( 'ERROR: No order ID or redirect URL' );
            wp_send_json_error( array( 'messages' => __( 'Checkout processed but unable to determine redirect URL.', 'woocommerce' ) ) );
            
        } catch ( Exception $e ) {
            ob_end_clean();
            $this->visa_log( 'EXCEPTION: ' . $e->getMessage() );
            $this->visa_log( 'Stack: ' . $e->getTraceAsString() );
            wp_send_json_error( array( 'messages' => $e->getMessage() ) );
        }
    }

    /** Clear tất cả session data liên quan đến visa form */
    private function clear_visa_session() {
        if ( WC()->session ) {
            WC()->session->__unset( 'visa_draft_data' );
            WC()->session->__unset( 'checkout' );
            WC()->session->__unset( 'order_awaiting_payment' );
        }
        if ( WC()->cart && ! WC()->cart->is_empty() ) {
            WC()->cart->empty_cart();
        }
        $this->visa_log( 'Session cleared: visa_draft_data, checkout, order_awaiting_payment, cart emptied' );
    }

    /** Hook: clear session khi xem trang order-received (đảm bảo clear dù checkout xử lý bởi WC hay plugin) */
    public function clear_visa_session_on_thankyou( $order_id ) {
        if ( ! $order_id || ! WC()->session ) {
            return;
        }
        WC()->session->__unset( 'visa_draft_data' );
        WC()->session->__unset( 'checkout' );
        WC()->session->__unset( 'order_awaiting_payment' );
    }

    public function save_order_meta($item, $key, $values, $order) {
        if(isset($values['visa_full_info'])) {
            $d = $values['visa_full_info'];
            $item->add_meta_data('Nationality', $d['nationality'] ?? '');
            $item->add_meta_data('Arrival Date', $d['arrival'] ?? '');
            
            // Lưu thông tin nhiều người
            if(isset($d['travelers']) && is_array($d['travelers'])) {
                $num_travelers = count($d['travelers']);
                $item->add_meta_data('Number of Travelers', $num_travelers);
                foreach($d['travelers'] as $idx => $traveler) {
                    $num = $idx + 1;
                    $item->add_meta_data("Traveler {$num} - Contact Name", $traveler['contact_name'] ?? '');
                    $item->add_meta_data("Traveler {$num} - Email", $traveler['email'] ?? '');
                    $item->add_meta_data("Traveler {$num} - Phone", $traveler['phone'] ?? '');
                    $item->add_meta_data("Traveler {$num} - Passport Link", $traveler['passport'] ?? '');
                    $item->add_meta_data("Traveler {$num} - Photo Link", $traveler['photo'] ?? '');
                }
            } else {
                // Fallback cho single traveler (backward compatibility)
                $item->add_meta_data('Passport Link', $d['passport'] ?? '');
                $item->add_meta_data('Photo Link', $d['photo'] ?? '');
            }
        }
    }

    // REMOVE FIELDS AGGRESSIVELY (Priority 9999)
    public function clean_checkout_fields($fields) {
        // Unset các field không cần thiết
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);
        unset($fields['shipping']);
        
        // Chỉ giữ required cho: first_name, email, phone
        // Bỏ required cho tất cả field khác (bao gồm last_name)
        if ( isset( $fields['billing'] ) ) {
            foreach ( $fields['billing'] as $key => $field ) {
                // Chỉ giữ required cho: billing_first_name, billing_email, billing_phone
                if ( ! in_array( $key, array( 'billing_first_name', 'billing_email', 'billing_phone' ) ) ) {
                    $fields['billing'][$key]['required'] = false;
                }
            }
        }
        
        return $fields;
    }
    // Also remove from default address fields to prevent validation errors
    public function clean_default_address_fields($fields) {
        unset($fields['postcode']);
        return $fields;
    }
    
    public function fix_checkout_endpoint($endpoint, $request) {
        // Đảm bảo checkout endpoint trả về JSON
        if($request === 'checkout' && wp_doing_ajax()) {
            return add_query_arg('wc-ajax', 'checkout', home_url('/'));
        }
        return $endpoint;
    }
}

new Visa_Wizard_V2_5();