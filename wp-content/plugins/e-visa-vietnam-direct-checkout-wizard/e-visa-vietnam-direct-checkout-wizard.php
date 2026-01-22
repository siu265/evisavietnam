<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Hệ thống Booking Visa (V1.7 - Legal Modals & Select2 Search).
Version: 1.7
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Wizard_V1_7 {

    public function __construct() {
        // Frontend & Core
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'visa_wizard_form', array( $this, 'render_wizard' ) );

        // Admin
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        // AJAX
        add_action( 'wp_ajax_visa_get_price', array( $this, 'ajax_get_price' ) );
        add_action( 'wp_ajax_nopriv_visa_get_price', array( $this, 'ajax_get_price' ) );
        
        add_action( 'wp_ajax_visa_upload_file', array( $this, 'ajax_upload_file' ) );
        add_action( 'wp_ajax_nopriv_visa_upload_file', array( $this, 'ajax_upload_file' ) );
        
        add_action( 'wp_ajax_visa_checkout', array( $this, 'ajax_checkout' ) );
        add_action( 'wp_ajax_nopriv_visa_checkout', array( $this, 'ajax_checkout' ) );

        // Woo & Redirect
        add_filter( 'woocommerce_checkout_fields', array( $this, 'clean_checkout_fields' ) );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_order_meta' ), 10, 4 );
        add_action( 'template_redirect', array( $this, 'redirect_cart_page' ) );
        add_action( 'wp_footer', array( $this, 'custom_checkout_script' ) );
    }

    /* ================= HELPER FUNCTIONS ================= */
    
    private function get_all_phone_codes() {
        return [
            '+84' => '🇻🇳 Vietnam (+84)', '+1' => '🇺🇸 United States (+1)', '+44' => '🇬🇧 United Kingdom (+44)',
            '+61' => '🇦🇺 Australia (+61)', '+1' => '🇨🇦 Canada (+1)', '+33' => '🇫🇷 France (+33)',
            '+49' => '🇩🇪 Germany (+49)', '+81' => '🇯🇵 Japan (+81)', '+82' => '🇰🇷 South Korea (+82)',
            '+91' => '🇮🇳 India (+91)', '+86' => '🇨🇳 China (+86)', '+65' => '🇸🇬 Singapore (+65)',
            '+66' => '🇹🇭 Thailand (+66)', '+62' => '🇮🇩 Indonesia (+62)', '+60' => '🇲🇾 Malaysia (+60)',
            '+63' => '🇵🇭 Philippines (+63)', '+7' => '🇷🇺 Russia (+7)', '+34' => '🇪🇸 Spain (+34)',
            '+39' => '🇮🇹 Italy (+39)', '+31' => '🇳🇱 Netherlands (+31)', '+41' => '🇨🇭 Switzerland (+41)',
            '+46' => '🇸🇪 Sweden (+46)', '+852' => '🇭🇰 Hong Kong (+852)', '+886' => '🇹🇼 Taiwan (+886)',
            '+90' => '🇹🇷 Turkey (+90)', '+971' => '🇦🇪 UAE (+971)', '+55' => '🇧🇷 Brazil (+55)',
            '+52' => '🇲🇽 Mexico (+52)', '+27' => '🇿🇦 South Africa (+27)',
        ];
    }

    private function get_attribute_label( $slug, $taxonomy ) {
        if ( taxonomy_exists( $taxonomy ) ) {
            $term = get_term_by( 'slug', $slug, $taxonomy );
            if ( $term && ! is_wp_error( $term ) ) return $term->name;
        }
        return ucwords( str_replace( '-', ' ', $slug ) );
    }

    /* ================= ADMIN SETTINGS ================= */

    public function add_admin_menu() {
        add_menu_page('Visa Options', 'Visa Options', 'manage_options', 'visa-options', array( $this, 'render_settings_page' ), 'dashicons-admin-site-alt3', 56 );
    }

    public function register_settings() {
        // Tab Nationality
        register_setting( 'visa_options_group_nationality', 'visa_nationalities_list' );
        
        // Tab General
        register_setting( 'visa_options_group_general', 'visa_work_days' );
        register_setting( 'visa_options_group_general', 'visa_work_start' );
        register_setting( 'visa_options_group_general', 'visa_work_end' );
        
        // Tab General - Legal Content
        register_setting( 'visa_options_group_general', 'visa_terms_content' );
        register_setting( 'visa_options_group_general', 'visa_privacy_content' );
        register_setting( 'visa_options_group_general', 'visa_refund_content' );
    }

    public function render_settings_page() {
        $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
        ?>
        <div class="wrap">
            <h1>Visa Booking Settings</h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=visa-options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
                <a href="?page=visa-options&tab=nationality" class="nav-tab <?php echo $active_tab == 'nationality' ? 'nav-tab-active' : ''; ?>">Nationality</a>
            </h2>
            
            <form method="post" action="options.php">
                <?php 
                if ( $active_tab == 'general' ) {
                    settings_fields( 'visa_options_group_general' );
                    do_settings_sections( 'visa_options_group_general' );
                    
                    // Work Schedule Data
                    $days = get_option('visa_work_days', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
                    $start_time = get_option('visa_work_start', '08:30');
                    $end_time = get_option('visa_work_end', '16:30');
                    
                    // Legal Content Data
                    $terms_content = get_option('visa_terms_content', '');
                    $privacy_content = get_option('visa_privacy_content', '');
                    $refund_content = get_option('visa_refund_content', '');
                    ?>
                    
                    <h3>Working Schedule</h3>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Ngày làm việc</th>
                            <td>
                                <?php 
                                $all_days = ['Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday'];
                                foreach($all_days as $key => $label): ?>
                                    <label style="margin-right:15px;"><input type="checkbox" name="visa_work_days[]" value="<?php echo $key; ?>" <?php if(in_array($key,(array)$days)) echo 'checked'; ?>> <?php echo $label; ?></label>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Giờ làm việc</th>
                            <td><input type="time" name="visa_work_start" value="<?php echo esc_attr($start_time); ?>"> đến <input type="time" name="visa_work_end" value="<?php echo esc_attr($end_time); ?>"></td>
                        </tr>
                    </table>
                    <hr>

                    <h3>Legal Policies (Modal Content)</h3>
                    
                    <h4 style="margin-bottom:5px;">1. Terms of Service</h4>
                    <?php wp_editor( $terms_content, 'visa_terms_content', ['textarea_rows' => 10, 'media_buttons' => false] ); ?>
                    
                    <h4 style="margin-bottom:5px; margin-top:20px;">2. Privacy Policy</h4>
                    <?php wp_editor( $privacy_content, 'visa_privacy_content', ['textarea_rows' => 10, 'media_buttons' => false] ); ?>
                    
                    <h4 style="margin-bottom:5px; margin-top:20px;">3. Refund Policy</h4>
                    <?php wp_editor( $refund_content, 'visa_refund_content', ['textarea_rows' => 10, 'media_buttons' => false] ); ?>

                    <?php submit_button();
                } 
                elseif ( $active_tab == 'nationality' ) {
                    settings_fields( 'visa_options_group_nationality' );
                    do_settings_sections( 'visa_options_group_nationality' );
                    $current_list = get_option( 'visa_nationalities_list', '' );
                    if( empty($current_list) ) {
                        $defaults = ['Vietnam', 'United States', 'United Kingdom', 'Australia', 'Canada', 'France', 'Germany', 'Japan', 'South Korea', 'India', 'China'];
                        $current_list = implode("\n", $defaults);
                    }
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Danh sách Quốc gia</th>
                            <td>
                                <textarea name="visa_nationalities_list" rows="15" cols="50" class="large-text code"><?php echo esc_textarea( $current_list ); ?></textarea>
                                <p class="description">Mỗi quốc gia một dòng.</p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button();
                }
                ?>
            </form>
        </div>
        <?php
    }

    /* ================= FRONTEND LOGIC ================= */

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
                        btn.text('← Change selection');
                        btn.attr('href', 'javascript:history.back()');
                    }
                }, 1000);
            });
            </script>
            <?php
        }
    }

    public function enqueue_assets() {
        wp_enqueue_script( 'jquery' );
        
        // Enqueue Select2 (Searchable Select)
        wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css' );
        wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), '4.0.13', true );

        wp_add_inline_style( 'wp-block-library', '
            /* Core Styles */
            .visa-wizard-container { background: #fff; margin: 0 auto; max-width: 100%; font-family: inherit; }
            .visa-sticky-header { background: #f4f5f8; padding: 15px 20px; border-bottom: 2px solid #ffaa17; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; margin-bottom: 20px; border-radius: 5px 5px 0 0; }
            .visa-header-title { font-weight: 700; font-size: 18px; color: #222; text-transform: uppercase; }
            .visa-total-price { font-size: 20px; color: #ffaa17; font-weight: 800; }
            .visa-total-price span.amount { color: #ffaa17 !important; font-weight: 800; }
            .visa-total-price bdi { display: inline-flex; align-items: center; }
            #visa_wizard .step-content { display: none !important; }
            #visa_wizard .step-content.active { display: block !important; animation: fadeIn 0.4s ease; }
            @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
            .visa-progress-container { background: #e5e5e5; height: 5px; width: 100%; margin-bottom: 25px; border-radius: 5px; overflow: hidden; }
            .visa-progress-bar { background: #ffaa17; height: 100%; width: 0%; transition: width 0.4s ease; }
            .visa-step-info { text-align: center; font-size: 14px; font-weight: 600; color: #848484; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
            
            /* Inputs */
            .visa-wizard-container select, .visa-wizard-container input[type="text"], .visa-wizard-container input[type="email"], .visa-wizard-container input[type="tel"], .visa-wizard-container input[type="date"] { display: block; width: 100%; height: 60px; padding: 10px 20px; font-size: 16px; color: #848484; background: #f4f5f8; border: 1px solid transparent; border-radius: 5px; margin-bottom: 0; box-shadow: none; }
            .visa-wizard-container select { -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"); background-repeat: no-repeat; background-position: right 20px center; background-size: 12px; }
            .visa-wizard-container select:focus, .visa-wizard-container input:focus { border-color: #ffaa17; background: #ffffff; outline: none; }
            .visa-wizard-container .form-group { margin-bottom: 20px; position: relative; }
            .visa-wizard-container .form-label { display: block; font-weight: 600; margin-bottom: 8px; color: #222; font-size: 15px; }
            .form-desc { font-size: 13px; color: #999; margin-top: 6px; font-style: italic; }
            
            /* Upload & Review */
            .file-upload-wrapper { position: relative; width: 100%; }
            .file-upload-wrapper input[type=file] { padding-top: 15px; }
            .upload-preview-box { margin-top: 15px; display: none; text-align: center; }
            .upload-preview-box img { max-height: 150px; border-radius: 5px; border: 2px solid #f4f5f8; padding: 5px; background: #fff; }
            .upload-status { font-size: 14px; margin-top: 5px; font-weight: 600; }
            .review-box { background: #f9f9f9; padding: 25px; border-radius: 5px; border: 1px solid #eee; }
            .review-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; border-bottom: 1px dashed #e0e0e0; padding-bottom: 8px; }
            .review-label { color: #848484; }
            .review-value { font-weight: 700; color: #222; text-align: right; }
            .visa-actions { margin-top: 30px; display: flex; justify-content: space-between; border-top: 1px solid #eee; padding-top: 30px; }
            .visa-wizard-container .btn-1 { padding: 15px 40px; font-weight: 700; text-transform: uppercase; color: #fff; background: #ffaa17; border: none; cursor: pointer; transition: all 0.3s ease; border-radius: 5px; font-size: 14px; }
            .visa-wizard-container .btn-1:hover { background: #222; color: #fff; }
            .visa-wizard-container .btn-back { background: #e5e5e5; color: #555; }
            .visa-wizard-container .btn-back:hover { background: #ccc; color: #333; }
            .error-message { background: #fff3cd; color: #856404; padding: 15px; margin-bottom: 20px; border: 1px solid #ffeeba; border-radius: 5px; display: none; }
            .input-error { border: 1px solid #ff3b30 !important; }

            /* Select2 Customization */
            .select2-container .select2-selection--single { height: 60px !important; background: #f4f5f8 !important; border: 1px solid transparent !important; border-radius: 5px !important; display: flex; align-items: center; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 60px !important; }
            .phone-group { display: flex; gap: 15px; }
            .phone-code-wrap { width: 35%; }
            .phone-number-wrap { width: 65%; }

            /* MODAL STYLES */
            .visa-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); }
            .visa-modal-content { background-color: #fff; margin: 5% auto; padding: 30px; border: 1px solid #888; width: 80%; max-width: 800px; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); position: relative; animation: slideDown 0.3s; }
            @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
            .visa-close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; transition: 0.2s; }
            .visa-close:hover { color: #000; text-decoration: none; }
            .visa-modal-body { max-height: 60vh; overflow-y: auto; margin-top: 15px; font-size: 14px; line-height: 1.6; color: #333; }
            .visa-link { color: #ffaa17; text-decoration: underline; cursor: pointer; font-weight: 600; }
            .visa-link:hover { color: #e69500; }
        ' );
    }

    public function render_wizard( $atts ) {
        $atts = shortcode_atts( ['product_id' => 0], $atts );
        $pid = intval( $atts['product_id'] );
        $product = wc_get_product( $pid );

        if ( ! $product || ! $product->is_type( 'variable' ) ) return '<p style="color:red; font-weight:bold;">ERROR: Invalid Product ID.</p>';

        $attributes = $product->get_variation_attributes();
        $attr_keys = array_keys( $attributes );
        $slug_type = ''; $slug_time = '';
        foreach($attr_keys as $key) {
            if(strpos($key, 'type') !== false || strpos($key, 'loai') !== false) $slug_type = $key;
            if(strpos($key, 'time') !== false || strpos($key, 'tgian') !== false || strpos($key, 'processing') !== false) $slug_time = $key;
        }
        if(empty($slug_type) && isset($attr_keys[0])) $slug_type = $attr_keys[0];
        if(empty($slug_time) && isset($attr_keys[1])) $slug_time = $attr_keys[1];

        // Prefill logic
        $prefill = [];
        if ( ! WC()->cart->is_empty() ) {
            $cart_items = WC()->cart->get_cart();
            $last_item = end($cart_items);
            if( isset($last_item['visa_full_info']) ) {
                $prefill = $last_item['visa_full_info'];
                $phone_parts = explode(' ', $prefill['phone'], 2);
                $prefill['phone_code'] = $phone_parts[0] ?? '';
                $prefill['phone_number'] = $phone_parts[1] ?? $prefill['phone'];
            }
            WC()->cart->empty_cart();
        }

        // Get Options
        $nationalities_str = get_option( 'visa_nationalities_list', '' );
        $nationalities = !empty($nationalities_str) ? array_filter(array_map('trim', explode("\n", $nationalities_str))) : ['Vietnam', 'USA', 'UK'];
        
        $work_days = get_option('visa_work_days', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
        $work_start = get_option('visa_work_start', '08:30');
        $work_end = get_option('visa_work_end', '16:30');
        $work_days_str = (count($work_days) == 5 && in_array('Mon',$work_days) && in_array('Fri',$work_days)) ? "Mon-Fri" : implode(', ', (array)$work_days);

        $phone_codes = $this->get_all_phone_codes();
        
        // Legal Content
        $terms_txt = wpautop(get_option('visa_terms_content', ''));
        $privacy_txt = wpautop(get_option('visa_privacy_content', ''));
        $refund_txt = wpautop(get_option('visa_refund_content', ''));

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
                    <input type="hidden" name="attr_slug_type" value="<?php echo esc_attr($slug_type); ?>">
                    <input type="hidden" name="attr_slug_time" value="<?php echo esc_attr($slug_time); ?>">

                    <div class="step-content active" data-step="1">
                        <div class="row clearfix">
                            <div class="col-lg-12 form-group"><h3 class="step-title">1. Where are you from?</h3></div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Nationality *</label>
                                <select name="nationality" class="form-control required-field">
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
                                <select name="visa_type" class="form-control price-trigger required-field" id="select_visa_type">
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
                                <select name="processing_time" class="form-control price-trigger required-field" id="select_processing_time">
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
                                <input type="date" name="arrival_date" class="form-control required-field" value="<?php echo esc_attr($prefill['arrival'] ?? ''); ?>">
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
                                    <input type="hidden" name="passport_url" id="passport_url" class="required-field" value="<?php echo esc_attr($prefill['passport'] ?? ''); ?>">
                                </div>
                                <div id="stat_passport" class="upload-status"></div>
                                <div class="upload-preview-box" id="prev_passport"><?php if(!empty($prefill['passport'])) echo '<img src="'.$prefill['passport'].'">'; ?></div>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label class="form-label">Portrait Photo *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="file_photo" accept="image/*" class="form-control">
                                    <input type="hidden" name="photo_url" id="photo_url" class="required-field" value="<?php echo esc_attr($prefill['photo'] ?? ''); ?>">
                                </div>
                                <div id="stat_photo" class="upload-status"></div>
                                <div class="upload-preview-box" id="prev_photo"><?php if(!empty($prefill['photo'])) echo '<img src="'.$prefill['photo'].'">'; ?></div>
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
                                        <select name="phone_code" class="form-control select2-enabled">
                                            <?php foreach($phone_codes as $code => $label): ?>
                                                <option value="<?php echo $code; ?>" <?php selected($prefill['phone_code'] ?? '+84', $code); ?>><?php echo $label; ?></option>
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

            <div id="modal_terms" class="visa-modal"><div class="visa-modal-content"><span class="visa-close">&times;</span><h3>Terms of Service</h3><div class="visa-modal-body"><?php echo $terms_txt; ?></div></div></div>
            <div id="modal_privacy" class="visa-modal"><div class="visa-modal-content"><span class="visa-close">&times;</span><h3>Privacy Policy</h3><div class="visa-modal-body"><?php echo $privacy_txt; ?></div></div></div>
            <div id="modal_refund" class="visa-modal"><div class="visa-modal-content"><span class="visa-close">&times;</span><h3>Refund Policy</h3><div class="visa-modal-body"><?php echo $refund_txt; ?></div></div></div>
        </div>

        <script>
        jQuery(document).ready(function($){
            let currentStep = 1;
            const totalSteps = 7;

            // Init Select2
            $('.select2-enabled').select2({ width: '100%' });

            // Init Modals
            $('.visa-link').click(function(){
                let target = '#' + $(this).data('target');
                $(target).fadeIn();
            });
            $('.visa-close, .visa-modal').click(function(e){
                if(e.target === this) $('.visa-modal').fadeOut();
            });

            $('.step-content').hide();
            $('.step-content[data-step="1"]').show();
            if($('#passport_url').val()) $('#prev_passport').show();
            if($('#photo_url').val()) $('#prev_photo').show();

            function showStep(step) {
                $('#global_error').hide();
                $('.step-content').removeClass('active').hide();
                $('.step-content[data-step="'+step+'"]').fadeIn(300).addClass('active');
                $('#current_step_num').text(step);
                $('#progress_bar').css('width', (step/totalSteps)*100 + '%');
                if(step === 1) $('#btn_back').hide(); else $('#btn_back').show();
                if(step === totalSteps) { $('#btn_next').hide(); $('#btn_submit').show(); populateReview(); }
                else { $('#btn_next').show(); $('#btn_submit').hide(); }
            }

            function validateStep(step) {
                let isValid = true;
                let currentPanel = $('.step-content[data-step="'+step+'"]');
                currentPanel.find('.required-field').filter(':input, select').each(function(){
                    if(!$(this).val() || $(this).val() === '') {
                        isValid = false; $(this).addClass('input-error');
                    } else { $(this).removeClass('input-error'); }
                });
                if(!isValid) $('#global_error').slideDown();
                return isValid;
            }

            $(document).on('change', '.required-field', function() {
                if($(this).val()) { $(this).removeClass('input-error'); $('#global_error').hide(); }
            });

            $('#btn_next').click(function(e){ e.preventDefault(); if(validateStep(currentStep)) { currentStep++; showStep(currentStep); } });
            $('#btn_back').click(function(e){ e.preventDefault(); currentStep--; showStep(currentStep); });

            $('.price-trigger').change(function(){
                let type = $('select[name="visa_type"]').val();
                let time = $('select[name="processing_time"]').val();
                if(type && time) {
                    $('#header_price_display').css('opacity', '0.5');
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                        action: 'visa_get_price', product_id: $('input[name="product_id"]').val(), type: type, time: time,
                        slug_type: $('input[name="attr_slug_type"]').val(), slug_time: $('input[name="attr_slug_time"]').val()
                    }, function(res){
                        $('#header_price_display').css('opacity', '1');
                        if(res.success) { $('#header_price_display').html(res.data.price_html); $('#variation_id').val(res.data.variation_id); }
                        else { $('#header_price_display').text('--'); }
                    });
                }
            });

            function setupUpload(id, hidden_id, msg_id, prev_id) {
                $(id).change(function(){
                    let fd = new FormData();
                    fd.append('file', this.files[0]); fd.append('action', 'visa_upload_file');
                    $(msg_id).text('Uploading...').css('color','#ffaa17');
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>', type: 'POST', contentType: false, processData: false, data: fd,
                        success: function(res){
                            if(res.success){
                                $(hidden_id).val(res.data.url); $(msg_id).text('Success').css('color','green');
                                $(prev_id).html('<img src="'+res.data.url+'">').fadeIn();
                                $(hidden_id).closest('.file-upload-wrapper').find('input').css('border-color', 'green');
                            } else { $(msg_id).text('Error').css('color','red'); }
                        }
                    });
                });
            }
            setupUpload('#file_passport', '#passport_url', '#stat_passport', '#prev_passport');
            setupUpload('#file_photo', '#photo_url', '#stat_photo', '#prev_photo');

            function populateReview() {
                $('#rev_nation').text($('select[name="nationality"]').val());
                let typeLabel = $('#select_visa_type option:selected').data('label'); $('#rev_type').text(typeLabel);
                let timeLabel = $('#select_processing_time option:selected').data('label'); $('#rev_time').text(timeLabel);
                $('#rev_date').text($('input[name="arrival_date"]').val());
                $('#rev_name').text($('input[name="fullname"]').val());
                $('#rev_email').text($('input[name="email"]').val());
                $('#rev_price').html($('#header_price_display').html());
            }

            $('#btn_submit').click(function(e){
                e.preventDefault();
                if(!$('#agree_terms').is(':checked')) { alert('Please accept terms.'); return; }
                let btn = $(this); btn.text('Processing...').prop('disabled', true);
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', { action: 'visa_checkout', data: $('#visa_form').serialize() }, function(res){
                    if(res.success) window.location.href = res.data.redirect;
                    else { alert(res.data.message); btn.text('PAY NOW').prop('disabled', false); }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    // --- BACKEND FUNCTIONS --- //
    public function ajax_get_price() {
        $pid = intval($_POST['product_id']);
        $match_attributes = [
            'attribute_' . sanitize_text_field($_POST['slug_type']) => sanitize_text_field($_POST['type']),
            'attribute_' . sanitize_text_field($_POST['slug_time']) => sanitize_text_field($_POST['time'])
        ];
        $data_store = WC_Data_Store::load( 'product' );
        $vid = $data_store->find_matching_product_variation( new WC_Product($pid), $match_attributes );
        if($vid) wp_send_json_success(['variation_id'=>$vid, 'price_html'=> wc_price(wc_get_product($vid)->get_price())]);
        wp_send_json_error();
    }

    public function ajax_upload_file() {
        if(!function_exists('wp_handle_upload')) require_once(ABSPATH.'wp-admin/includes/file.php');
        $up = wp_handle_upload($_FILES['file'], ['test_form'=>false]);
        if(isset($up['url'])) wp_send_json_success(['url'=>$up['url']]); else wp_send_json_error();
    }

    public function ajax_checkout() {
        parse_str($_POST['data'], $form);
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
        if(WC()->cart->add_to_cart( $form['product_id'], 1, $form['variation_id'], [], $custom_data )) {
            $c = WC()->customer;
            $c->set_billing_first_name($form['fullname']); $c->set_billing_email($form['email']); $c->set_billing_phone($full_phone);
            $c->set_billing_country('VN'); $c->set_billing_address_1('Online App'); $c->set_billing_city('Hanoi'); $c->set_billing_postcode('10000');
            $c->save();
            wp_send_json_success(['redirect' => wc_get_checkout_url()]);
        } else wp_send_json_error(['message' => 'Error adding to cart.']);
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
}

new Visa_Wizard_V1_7();