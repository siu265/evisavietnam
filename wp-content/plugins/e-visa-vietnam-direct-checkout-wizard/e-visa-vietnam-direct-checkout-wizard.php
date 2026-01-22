<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Hệ thống Booking Visa V2.4 (Fix lỗi 404 Ajax URL & Critical Checkout).
Version: 2.4
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Wizard_V2_4 {

    public function __construct() {
        // Assets & Logic
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

        // Woo
        add_filter( 'woocommerce_checkout_fields', array( $this, 'clean_checkout_fields' ), 9999 );
        add_filter( 'woocommerce_default_address_fields', array( $this, 'clean_default_address_fields' ), 9999 );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_order_meta' ), 10, 4 );
        add_action( 'template_redirect', array( $this, 'redirect_cart_page' ) );
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
        register_setting( 'visa_group', 'visa_terms_content' );
        register_setting( 'visa_group', 'visa_privacy_content' );
        register_setting( 'visa_group', 'visa_refund_content' );
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
                <?php if ( $active_tab == 'general' ): 
                    $days = get_option('visa_work_days', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
                    $start = get_option('visa_work_start', '08:30');
                    $end = get_option('visa_work_end', '16:30');
                ?>
                    <div class="visa-card">
                        <h2>Schedule</h2>
                        <table class="form-table">
                            <tr><th>Days</th><td><?php $all_days = ['Mon'=>'Mon','Tue'=>'Tue','Wed'=>'Wed','Thu'=>'Thu','Fri'=>'Fri','Sat'=>'Sat','Sun'=>'Sun']; foreach($all_days as $key => $label): ?><label style="margin-right:15px;"><input type="checkbox" name="visa_work_days[]" value="<?php echo $key; ?>" <?php if(in_array($key,(array)$days)) echo 'checked'; ?>> <?php echo $label; ?></label><?php endforeach; ?></td></tr>
                            <tr><th>Hours</th><td><input type="time" name="visa_work_start" value="<?php echo esc_attr($start); ?>"> to <input type="time" name="visa_work_end" value="<?php echo esc_attr($end); ?>"></td></tr>
                        </table>
                    </div>
                    <div class="visa-card">
                        <h2>Policies</h2>
                        <div style="margin-bottom:20px;"><label>Terms</label><?php wp_editor( get_option('visa_terms_content'), 'visa_terms_content', ['textarea_rows'=>5,'media_buttons'=>false] ); ?></div>
                        <div style="margin-bottom:20px;"><label>Privacy</label><?php wp_editor( get_option('visa_privacy_content'), 'visa_privacy_content', ['textarea_rows'=>5,'media_buttons'=>false] ); ?></div>
                        <div><label>Refund</label><?php wp_editor( get_option('visa_refund_content'), 'visa_refund_content', ['textarea_rows'=>5,'media_buttons'=>false] ); ?></div>
                    </div>
                <?php elseif ( $active_tab == 'nationality' ): 
                    $current_list = get_option( 'visa_nationalities_list', '' );
                    if(empty($current_list)) $current_list = "Vietnam\nUSA\nUK";
                ?>
                    <div class="visa-card"><h2>Nationalities</h2><textarea name="visa_nationalities_list" rows="20" class="large-text code"><?php echo esc_textarea( $current_list ); ?></textarea></div>
                <?php endif; ?>
                <div class="visa-submit-bar"><?php submit_button('Save Changes', 'primary large', 'submit', false); ?></div>
            </form>
        </div>
        <?php
    }

    /* ================= 2. FRONTEND LOGIC ================= */

    private function get_all_phone_codes() {
        return [ '+84'=>'🇻🇳 Vietnam (+84)', '+1'=>'🇺🇸 United States (+1)', '+44'=>'🇬🇧 United Kingdom (+44)', '+61'=>'🇦🇺 Australia (+61)', '+1'=>'🇨🇦 Canada (+1)', '+33'=>'🇫🇷 France (+33)', '+49'=>'🇩🇪 Germany (+49)', '+81'=>'🇯🇵 Japan (+81)', '+82'=>'🇰🇷 South Korea (+82)', '+91'=>'🇮🇳 India (+91)', '+86'=>'🇨🇳 China (+86)', '+65'=>'🇸🇬 Singapore (+65)', '+66'=>'🇹🇭 Thailand (+66)', '+62'=>'🇮🇩 Indonesia (+62)', '+60'=>'🇲🇾 Malaysia (+60)', '+63'=>'🇵🇭 Philippines (+63)', '+7'=>'🇷🇺 Russia (+7)', '+34'=>'🇪🇸 Spain (+34)', '+39'=>'🇮🇹 Italy (+39)', '+31'=>'🇳🇱 Netherlands (+31)', '+41'=>'🇨🇭 Switzerland (+41)', '+46'=>'🇸🇪 Sweden (+46)', '+852'=>'🇭🇰 Hong Kong (+852)', '+886'=>'🇹🇼 Taiwan (+886)', '+90'=>'🇹🇷 Turkey (+90)', '+971'=>'🇦🇪 UAE (+971)', '+55'=>'🇧🇷 Brazil (+55)' ];
    }

    private function get_attribute_label( $slug, $taxonomy ) {
        if ( taxonomy_exists( $taxonomy ) ) {
            $term = get_term_by( 'slug', $slug, $taxonomy );
            if ( $term && ! is_wp_error( $term ) ) return $term->name;
        }
        return ucwords( str_replace( '-', ' ', $slug ) );
    }

    public function enqueue_assets() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css' );
        wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '4.0.13', true );

        wp_add_inline_style( 'wp-block-library', '
            .visa-wizard-container { background: #fff; margin: 0 auto; max-width: 100%; font-family: inherit; position: relative; }
            .visa-sticky-header { background: #f4f5f8; padding: 15px 20px; border-bottom: 2px solid #ffaa17; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 90; border-radius: 5px 5px 0 0; }
            .visa-header-title { font-weight: 700; font-size: 18px; color: #222; text-transform: uppercase; }
            .visa-total-price { font-size: 20px; color: #ffaa17; font-weight: 800; }
            .visa-total-price bdi { display: inline-flex; align-items: center; }
            #visa_wizard .step-content { display: none !important; }
            #visa_wizard .step-content.active { display: block !important; animation: fadeIn 0.4s ease; }
            @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
            .visa-progress-container { background: #e5e5e5; height: 5px; width: 100%; margin-bottom: 25px; border-radius: 5px; overflow: hidden; }
            .visa-progress-bar { background: #ffaa17; height: 100%; width: 0%; transition: width 0.4s ease; }
            .visa-step-info { text-align: center; font-size: 14px; font-weight: 600; color: #848484; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
            .visa-wizard-container .form-control { display: block; width: 100%; height: 50px; padding: 10px 15px; font-size: 16px; color: #333; background: #f4f5f8; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 0; box-shadow: none; }
            .visa-wizard-container .form-group { margin-bottom: 25px; position: relative; }
            .visa-wizard-container .form-label { display: block; font-weight: 600; margin-bottom: 8px; color: #222; font-size: 15px; }
            .form-desc { font-size: 13px; color: #999; margin-top: 6px; font-style: italic; }
            .select2-container .select2-selection--single { height: 50px !important; background: #f4f5f8 !important; border: 1px solid #ddd !important; border-radius: 5px !important; display: flex !important; align-items: center !important; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 50px !important; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { color: #333 !important; padding-left: 15px !important; font-size: 16px; }
            .file-upload-wrapper input[type=file] { padding-top: 10px; }
            .upload-preview-box { margin-top: 10px; display: none; }
            .upload-preview-box img { height: 100px; border-radius: 4px; border: 1px solid #ccc; padding: 2px; }
            .review-box { background: #f9f9f9; padding: 25px; border-radius: 5px; border: 1px solid #eee; }
            .review-item { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; border-bottom: 1px dashed #e0e0e0; padding-bottom: 5px; }
            .review-value { font-weight: 700; color: #222; text-align: right; }
            .visa-actions { margin-top: 30px; display: flex; justify-content: space-between; border-top: 1px solid #eee; padding-top: 30px; }
            .visa-wizard-container .btn-1 { padding: 12px 35px; font-weight: 700; text-transform: uppercase; color: #fff; background: #ffaa17; border: none; cursor: pointer; border-radius: 5px; font-size: 14px; }
            .visa-wizard-container .btn-1:hover { background: #222; }
            .visa-wizard-container .btn-back { background: #e5e5e5; color: #555; }
            .visa-wizard-container .btn-back:hover { background: #ccc; }
            .error-message { background: #fff3cd; color: #856404; padding: 15px; margin-bottom: 20px; border: 1px solid #ffeeba; border-radius: 5px; display: none; }
            .input-error { border: 1px solid #ff3b30 !important; }
            .select2-container .select2-selection.input-error { border: 1px solid #ff3b30 !important; }
            .visa-modal { display: none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); align-items: center; justify-content: center; }
            .visa-modal-content { background-color: #fff; margin: 5% auto; padding: 30px; border: 1px solid #888; width: 90%; max-width: 700px; border-radius: 8px; box-shadow: 0 15px 50px rgba(0,0,0,0.5); position: relative; animation: slideDown 0.3s; }
            @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
            .visa-close { position: absolute; right: 15px; top: 10px; color: #aaa; font-size: 30px; font-weight: bold; cursor: pointer; z-index: 10; }
            .visa-close:hover { color: #000; }
            .visa-modal-body { max-height: 70vh; overflow-y: auto; margin-top: 10px; font-size: 14px; line-height: 1.6; color: #333; }
            .visa-link { color: #ffaa17; text-decoration: underline; cursor: pointer; font-weight: 600; }
        ' );
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
                        btn.text('← Start Over'); 
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

        $phone_codes = $this->get_all_phone_codes();
        
        ob_start();
        ?>
        <div class="visa-wizard-container contact-section" id="visa_wizard">
            <div class="form-inner">
                <div class="visa-sticky-header">
                    <div class="visa-header-title">Apply For Visa</div>
                    <div class="visa-total-price" id="header_price_display">--</div>
                </div>
                <div class="visa-step-info">Step <span id="current_step_num">1</span> of 7</div>
                <div class="visa-progress-container"><div class="visa-progress-bar" id="progress_bar"></div></div>
                <div id="global_error" class="error-message">Please fill in all required fields.</div>

                <form id="visa_form">
                    <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                    <input type="hidden" name="variation_id" id="variation_id">
                    
                    <div class="step-content active" data-step="1">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">1. Where are you from?</h3></div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Nationality *</label>
                                <select name="nationality" class="form-control required-field select2-enable">
                                    <option value="">-- Select Country --</option>
                                    <?php foreach($nationalities as $n): ?>
                                        <option value="<?php echo esc_attr($n); ?>" <?php selected($prefill['nationality'] ?? '', $n); ?>><?php echo esc_html($n); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="form-desc">Select the nationality on your passport.</p>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="2">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">2. Select Visa Type</h3></div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Visa Type *</label>
                                <select name="visa_type" class="form-control price-trigger required-field select2-enable" id="select_visa_type">
                                    <option value="">-- Select Option --</option>
                                    <?php if(isset($attributes[$slug_type])): foreach($attributes[$slug_type] as $term_slug): 
                                        $term_label = $this->get_attribute_label($term_slug, $slug_type); ?>
                                        <option value="<?php echo esc_attr($term_slug); ?>" data-label="<?php echo esc_attr($term_label); ?>"><?php echo esc_html($term_label); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="3">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">3. Processing Time</h3></div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Processing Time *</label>
                                <select name="processing_time" class="form-control price-trigger required-field select2-enable" id="select_processing_time">
                                    <option value="">-- Select Option --</option>
                                    <?php if(isset($attributes[$slug_time])): foreach($attributes[$slug_time] as $term_slug): 
                                        $term_label = $this->get_attribute_label($term_slug, $slug_time); ?>
                                        <option value="<?php echo esc_attr($term_slug); ?>" data-label="<?php echo esc_attr($term_label); ?>"><?php echo esc_html($term_label); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <p class="form-desc" style="color:#ffaa17;">Note: Working hours <?php echo esc_html($work_start . ' - ' . $work_end . ' (' . $work_days_str . ')'); ?>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="4">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">4. Date of Arrival</h3></div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Arrival Date *</label>
                                <input type="date" name="arrival_date" class="form-control required-field" value="<?php echo esc_attr($prefill['arrival_date'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="5">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">5. Documents Upload</h3></div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="form-label">Passport Photo *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="file_passport" accept="image/*" class="form-control">
                                    <input type="hidden" name="passport_url" id="passport_url" class="required-field" value="<?php echo esc_attr($prefill['passport_url'] ?? ''); ?>">
                                </div>
                                <div id="stat_passport" class="upload-status"></div>
                                <div class="upload-preview-box" id="prev_passport"><?php if(!empty($prefill['passport_url'])) echo '<img src="'.$prefill['passport_url'].'">'; ?></div>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="form-label">Portrait Photo *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="file_photo" accept="image/*" class="form-control">
                                    <input type="hidden" name="photo_url" id="photo_url" class="required-field" value="<?php echo esc_attr($prefill['photo_url'] ?? ''); ?>">
                                </div>
                                <div id="stat_photo" class="upload-status"></div>
                                <div class="upload-preview-box" id="prev_photo"><?php if(!empty($prefill['photo_url'])) echo '<img src="'.$prefill['photo_url'].'">'; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="6">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">6. Contact Details</h3></div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="fullname" class="form-control required-field" placeholder="Enter full name" value="<?php echo esc_attr($prefill['fullname'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control required-field" placeholder="name@example.com" value="<?php echo esc_attr($prefill['email'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Phone Number *</label>
                                <div class="phone-group">
                                    <div class="phone-code-wrap">
                                        <select name="phone_code" class="form-control select2-enable">
                                            <?php foreach($phone_codes as $code => $label): ?>
                                                <option value="<?php echo $code; ?>" <?php selected('+84', $code); ?>><?php echo $label; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="phone-number-wrap">
                                        <input type="tel" name="phone_number" class="form-control required-field" placeholder="Phone Number" value="<?php echo esc_attr($prefill['phone_number'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="7">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">7. Review & Pay</h3></div>
                            <div class="col-lg-12">
                                <div class="review-box">
                                    <div class="review-item"><span>Nationality:</span> <span class="review-value" id="rev_nation">--</span></div>
                                    <div class="review-item"><span>Visa Type:</span> <span class="review-value" id="rev_type">--</span></div>
                                    <div class="review-item"><span>Time:</span> <span class="review-value" id="rev_time">--</span></div>
                                    <div class="review-item"><span>Arrival:</span> <span class="review-value" id="rev_date">--</span></div>
                                    <div class="review-item"><span>Name:</span> <span class="review-value" id="rev_name">--</span></div>
                                    <div class="review-item"><span>Email:</span> <span class="review-value" id="rev_email">--</span></div>
                                    <div class="review-item" style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
                                        <span style="font-weight:bold; font-size:18px;">Total:</span> <span class="review-value" id="rev_price" style="color:#ffaa17; font-size:22px;">--</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group" style="margin-top: 25px;">
                                <label style="cursor:pointer; display: flex; align-items: start;">
                                    <input type="checkbox" id="agree_terms" style="width:20px; height:20px; margin-right:10px; margin-top:3px;"> 
                                    <span style="font-size: 14px; color: #555;">
                                        I acknowledge that I have read and accept the 
                                        <span class="visa-link" data-target="modal_terms">Terms of Service</span>, 
                                        <span class="visa-link" data-target="modal_privacy">Privacy Policy</span>, and 
                                        <span class="visa-link" data-target="modal_refund">Refund Policy</span>.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="visa-actions">
                        <button type="button" class="btn-1 btn-back" id="btn_back" style="display:none;">Back</button>
                        <button type="button" class="btn-1 btn-next" id="btn_next">Next Step</button>
                        <button type="button" class="btn-1 btn-checkout" id="btn_submit" style="display:none;">PAY NOW</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($){
            let currentStep = 1; const totalSteps = 7;
            $(".select2-enable").select2({ width: "100%" });

            <?php if(!empty($prefill['visa_type'])): ?>
                $("select[name='visa_type']").val("<?php echo esc_js($prefill['visa_type']); ?>").trigger("change");
            <?php endif; ?>
            <?php if(!empty($prefill['processing_time'])): ?>
                $("select[name='processing_time']").val("<?php echo esc_js($prefill['processing_time']); ?>").trigger("change");
            <?php endif; ?>
            <?php if(!empty($prefill['phone_code'])): ?>
                $("select[name='phone_code']").val("<?php echo esc_js($prefill['phone_code']); ?>").trigger("change");
            <?php endif; ?>

            // Modal
            $(document).on("click", ".visa-link", function(e){
                e.preventDefault(); e.stopPropagation();
                let target = "#" + $(this).data("target");
                $(target).css("display", "flex").hide().fadeIn();
            });
            $(document).on("click", ".visa-close, .visa-modal", function(e){
                if(e.target === this || $(this).hasClass("visa-close")) $(".visa-modal").fadeOut();
            });

            $(".step-content").hide(); $(".step-content[data-step=\"1\"]").show();
            if($("#passport_url").val()) $("#prev_passport").show();
            if($("#photo_url").val()) $("#prev_photo").show();

            function showStep(step) {
                $("#global_error").hide();
                $(".step-content").removeClass("active").hide();
                $(".step-content[data-step=\""+step+"\"]").fadeIn(300).addClass("active");
                $("#current_step_num").text(step);
                $("#progress_bar").css("width", (step/totalSteps)*100 + "%");
                if(step === 1) $("#btn_back").hide(); else $("#btn_back").show();
                if(step === totalSteps) { $("#btn_next").hide(); $("#btn_submit").show(); populateReview(); }
                else { $("#btn_next").show(); $("#btn_submit").hide(); }
            }

            function validateStep(step) {
                let isValid = true;
                let currentPanel = $(".step-content[data-step=\""+step+"\"]");
                currentPanel.find(".required-field").filter(":input, select").each(function(){
                    if(!$(this).val() || $(this).val() === "") {
                        isValid = false; $(this).addClass("input-error");
                        if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").addClass("input-error"); }
                    } else { 
                        $(this).removeClass("input-error"); 
                        if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").removeClass("input-error"); }
                    }
                });
                if(!isValid) $("#global_error").slideDown();
                return isValid;
            }

            $(document).on("change", ".required-field", function() {
                if($(this).val()) { 
                    $(this).removeClass("input-error"); 
                    if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").removeClass("input-error"); }
                    $("#global_error").hide(); 
                }
            });

            $("#btn_next").click(function(e){ e.preventDefault(); if(validateStep(currentStep)) { currentStep++; showStep(currentStep); } });
            $("#btn_back").click(function(e){ e.preventDefault(); currentStep--; showStep(currentStep); });

            // SMART PRICE CALCULATION (V2.4 Correct URL Fix)
            $(".price-trigger").change(function(){
                let type = $("select[name=\"visa_type\"]").val();
                let time = $("select[name=\"processing_time\"]").val();
                if(type && time) {
                    $("#header_price_display").css("opacity", "0.5");
                    // FIX: Output proper URL with PHP echo
                    $.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                        action: "visa_get_price", 
                        product_id: $("input[name=\"product_id\"]").val(), 
                        type: type, 
                        time: time
                    }, function(res){
                        $("#header_price_display").css("opacity", "1");
                        if(res.success) { 
                            $("#header_price_display").html(res.data.price_html); 
                            $("#variation_id").val(res.data.variation_id); 
                        } else { 
                            $("#header_price_display").text("--"); 
                            $("#variation_id").val("");
                        }
                    });
                }
            });

            function setupUpload(id, hidden_id, msg_id, prev_id) {
                $(id).change(function(){
                    let fd = new FormData(); fd.append("file", this.files[0]); fd.append("action", "visa_upload_file");
                    $(msg_id).text("Uploading...").css("color","#ffaa17");
                    // FIX: Output proper URL with PHP echo
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>", type: "POST", contentType: false, processData: false, data: fd,
                        success: function(res){
                            if(res.success){
                                $(hidden_id).val(res.data.url); $(msg_id).text("Success").css("color","green");
                                $(prev_id).html("<img src=\""+res.data.url+"\">").fadeIn().show();
                                $(hidden_id).closest(".file-upload-wrapper").find("input").css("border-color", "green");
                            } else { $(msg_id).text("Error").css("color","red"); }
                        }
                    });
                });
            }
            setupUpload("#file_passport", "#passport_url", "#stat_passport", "#prev_passport");
            setupUpload("#file_photo", "#photo_url", "#stat_photo", "#prev_photo");

            function populateReview() {
                $("#rev_nation").text($("select[name=\"nationality\"]").val());
                let typeLabel = $("#select_visa_type option:selected").data("label"); $("#rev_type").text(typeLabel);
                let timeLabel = $("#select_processing_time option:selected").data("label"); $("#rev_time").text(timeLabel);
                $("#rev_date").text($("input[name=\"arrival_date\"]").val());
                $("#rev_name").text($("input[name=\"fullname\"]").val());
                $("#rev_email").text($("input[name=\"email\"]").val());
                $("#rev_price").html($("#header_price_display").html());
            }

            $("#btn_submit").click(function(e){
                e.preventDefault();
                if(!$("#agree_terms").is(":checked")) { alert("Please accept terms."); return; }
                
                if(!$("#variation_id").val()) {
                    alert("Please re-select Visa Type or Processing Time (Price not calculated).");
                    return;
                }

                let btn = $(this); btn.text("Processing...").prop("disabled", true);
                // FIX: Output proper URL with PHP echo
                $.post("<?php echo admin_url('admin-ajax.php'); ?>", { action: "visa_checkout", data: $("#visa_form").serialize() }, function(res){
                    if(res.success) window.location.href = res.data.redirect;
                    else { alert(res.data.message); btn.text("PAY NOW").prop("disabled", false); }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    // --- BACKEND FUNCTIONS (V2.4 ROBUST FIX) --- //
    public function ajax_get_price() {
        $pid = intval($_POST['product_id']);
        $selected_type = sanitize_text_field($_POST['type']);
        $selected_time = sanitize_text_field($_POST['time']);
        
        $product = wc_get_product($pid);
        if (!$product) { wp_send_json_error(['message' => 'Invalid Product']); }

        $variations = $product->get_available_variations();
        $matched_vid = 0;
        $price_html = '';

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
                break;
            }
        }

        if ($matched_vid) {
            wp_send_json_success(['variation_id' => $matched_vid, 'price_html' => $price_html]);
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
        $full_phone = $form['phone_code'] . ' ' . $form['phone_number'];
        $custom_data = [
            'visa_full_info' => [
                'nationality' => $form['nationality'], 'arrival' => $form['arrival_date'],
                'passport' => $form['passport_url'], 'photo' => $form['photo_url'],
                'fullname' => $form['fullname'], 'email' => $form['email'], 'phone' => $full_phone,
                'phone_code' => $form['phone_code'], 'phone_number' => $form['phone_number']
            ]
        ];
        
        if ( empty($form['variation_id']) ) {
            wp_send_json_error(['message' => 'Missing Price/Variation ID. Please re-select options.']);
        }

        if(WC()->cart->add_to_cart( $form['product_id'], 1, $form['variation_id'], [], $custom_data )) {
            $c = WC()->customer;
            $c->set_billing_first_name($form['fullname']); $c->set_billing_email($form['email']); $c->set_billing_phone($full_phone);
            $c->set_billing_country('VN'); $c->set_billing_address_1('Online App'); $c->set_billing_city('Hanoi'); $c->set_billing_postcode('10000');
            $c->save();
            wp_send_json_success(['redirect' => wc_get_checkout_url()]);
        } else wp_send_json_error(['message' => 'Error adding to cart. Please try again.']);
    }

    public function save_order_meta($item, $key, $values, $order) {
        if(isset($values['visa_full_info'])) {
            $d = $values['visa_full_info'];
            $item->add_meta_data('Nationality', $d['nationality']); $item->add_meta_data('Arrival Date', $d['arrival']);
            $item->add_meta_data('Passport Link', $d['passport']); $item->add_meta_data('Photo Link', $d['photo']);
        }
    }

    public function clean_checkout_fields($fields) {
        unset($fields['billing']['billing_company'], $fields['billing']['billing_address_1'], $fields['billing']['billing_address_2'], $fields['billing']['billing_city'], $fields['billing']['billing_postcode'], $fields['billing']['billing_state'], $fields['shipping']);
        return $fields;
    }
    public function clean_default_address_fields($fields) {
        unset($fields['postcode']);
        return $fields;
    }
}

new Visa_Wizard_V2_4();