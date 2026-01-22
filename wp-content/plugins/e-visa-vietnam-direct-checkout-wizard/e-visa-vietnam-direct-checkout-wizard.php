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

        // Woo Hooks (Postcode Removal Logic)
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
                <?php
                $days = get_option('visa_work_days', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
                $start = get_option('visa_work_start', '08:30');
                $end = get_option('visa_work_end', '16:30');
                $current_list = get_option( 'visa_nationalities_list', '' );
                if ( empty( $current_list ) ) $current_list = "Japan\nSouth Korea\nAustralia\nUnited States\nCanada\nChina\nTaiwan\nFrance\nGermany\nNew Zealand";
                ?>
                <div class="visa-tab-panel" id="tab-general" style="<?php echo $active_tab !== 'general' ? 'display:none;' : ''; ?>">
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

                    <div class="step-content" data-step="2">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">2</span>Visa Type</h3>
                            <p class="visa-step-desc">Select the type of entry and validity duration suitable for your trip.</p>
                            <div class="form-group">
                                <select name="visa_type" class="form-control price-trigger required-field select2-enable" id="select_visa_type" data-placeholder="Select visa type">
                                    <option value="">Select visa type</option>
                                    <?php if(isset($attributes[$slug_type])): foreach($attributes[$slug_type] as $term_slug): 
                                        $term_label = $this->get_attribute_label($term_slug, $slug_type); ?>
                                        <option value="<?php echo esc_attr($term_slug); ?>" data-label="<?php echo esc_attr($term_label); ?>"><?php echo esc_html($term_label); ?></option>
                                    <?php endforeach; endif; ?>
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
                                    <?php if(isset($attributes[$slug_time])): foreach($attributes[$slug_time] as $term_slug): 
                                        $term_label = $this->get_attribute_label($term_slug, $slug_time); ?>
                                        <option value="<?php echo esc_attr($term_slug); ?>" data-label="<?php echo esc_attr($term_label); ?>"><?php echo esc_html($term_label); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <p class="visa-step-note">Working hours: <?php echo esc_html($work_start . ' – ' . $work_end . ' (' . $work_days_str . ')'); ?></p>
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
                            <h3 class="step-title"><span class="visa-step-badge">5</span>Upload Documents</h3>
                            <p class="visa-step-desc">Upload clear photos of your passport data page and a recent portrait.</p>
                            <div class="visa-upload-grid">
                                <div class="form-group">
                                    <div class="file-upload-wrapper">
                                        <input type="file" id="file_passport" accept="image/*" class="form-control">
                                        <input type="hidden" name="passport_url" id="passport_url" class="required-field" value="<?php echo esc_attr($prefill['passport_url'] ?? ''); ?>">
                                    </div>
                                    <div id="stat_passport" class="upload-status"></div>
                                    <div class="upload-preview-box" id="prev_passport"><?php if(!empty($prefill['passport_url'])) echo '<img src="'.$prefill['passport_url'].'">'; ?></div>
                                </div>
                                <div class="form-group">
                                    <div class="file-upload-wrapper">
                                        <input type="file" id="file_photo" accept="image/*" class="form-control">
                                        <input type="hidden" name="photo_url" id="photo_url" class="required-field" value="<?php echo esc_attr($prefill['photo_url'] ?? ''); ?>">
                                    </div>
                                    <div id="stat_photo" class="upload-status"></div>
                                    <div class="upload-preview-box" id="prev_photo"><?php if(!empty($prefill['photo_url'])) echo '<img src="'.$prefill['photo_url'].'">'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="6">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">6</span>Contact Information</h3>
                            <p class="visa-step-desc">Provide your full name, email, and phone number for application updates.</p>
                            <div class="form-group">
                                <input type="text" name="fullname" class="form-control required-field" placeholder="Full name" value="<?php echo esc_attr($prefill['fullname'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control required-field" placeholder="Email address" value="<?php echo esc_attr($prefill['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group phone-group">
                                <div class="phone-code-wrap">
                                    <select name="phone_code" class="form-control select2-enable">
                                        <?php foreach($phone_codes as $code => $label): ?>
                                            <option value="<?php echo $code; ?>" <?php selected($prefill['phone_code'] ?? '+84', $code); ?>><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="phone-number-wrap">
                                    <input type="tel" name="phone_number" class="form-control required-field" placeholder="Phone number" value="<?php echo esc_attr($prefill['phone_number'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="7">
                        <div class="visa-step-inner">
                            <h3 class="step-title"><span class="visa-step-badge">7</span>Review & Payment</h3>
                            <p class="visa-step-desc">Verify your application details and proceed to secure payment to finalize.</p>
                            <div class="review-box">
                                <div class="review-item"><span>Nationality:</span> <span class="review-value" id="rev_nation">--</span></div>
                                <div class="review-item"><span>Visa Type:</span> <span class="review-value" id="rev_type">--</span></div>
                                <div class="review-item"><span>Time:</span> <span class="review-value" id="rev_time">--</span></div>
                                <div class="review-item"><span>Arrival:</span> <span class="review-value" id="rev_date">--</span></div>
                                <div class="review-item"><span>Name:</span> <span class="review-value" id="rev_name">--</span></div>
                                <div class="review-item"><span>Email:</span> <span class="review-value" id="rev_email">--</span></div>
                                <div class="review-item"><span>Phone:</span> <span class="review-value" id="rev_phone">--</span></div>
                                <div class="review-item review-total">
                                    <span>Total:</span> <span class="review-value" id="rev_price">--</span>
                                </div>
                            </div>
                            <div class="form-group visa-terms-wrap">
                                <label class="visa-terms-label">
                                    <input type="checkbox" id="agree_terms">
                                    <span>I acknowledge that I have read and accept the <span class="visa-link" data-target="modal_terms">Terms of Service</span>, <span class="visa-link" data-target="modal_privacy">Privacy Policy</span>, and <span class="visa-link" data-target="modal_refund">Refund Policy</span>.</span>
                                </label>
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
            </div>
        </div>

        <script>
        jQuery(document).ready(function($){
            let currentStep = 1; const totalSteps = 7;
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

            showStep(1);
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
                        // Validate phone number format (step 6 - Contact Information)
                        if($(this).attr("name") === "phone_number" && step === 6) {
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
                if($(this).val()) { 
                    $(this).removeClass("input-error"); 
                    if($(this).hasClass("select2-hidden-accessible")) { $(this).next(".select2-container").find(".select2-selection").removeClass("input-error"); }
                    $("#global_error").hide(); 
                }
            });

            // Phone number validation: chỉ cho phép số
            $(document).on("input", "input[name='phone_number']", function() {
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

            $("#btn_next").click(function(e){ e.preventDefault(); if(validateStep(currentStep)) { currentStep++; showStep(currentStep); } });
            $("#btn_back").click(function(e){ e.preventDefault(); currentStep--; showStep(currentStep); });

            // SMART PRICE CALCULATION
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
            $c->set_billing_country('VN'); $c->set_billing_address_1('Online App'); $c->set_billing_city('Hanoi'); $c->set_billing_postcode('');
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

    // REMOVE FIELDS AGGRESSIVELY (Priority 9999)
    public function clean_checkout_fields($fields) {
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']); // Standard Unset
        unset($fields['billing']['billing_state']);
        unset($fields['shipping']);
        return $fields;
    }
    // Also remove from default address fields to prevent validation errors
    public function clean_default_address_fields($fields) {
        unset($fields['postcode']);
        return $fields;
    }
}

new Visa_Wizard_V2_5();