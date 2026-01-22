<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Multi-step E-Visa Vietnam Booking Form with Direct Checkout and Auto-Clean Session.
Version: 1.1
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Wizard_Themed {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'visa_wizard_form', array( $this, 'render_wizard' ) );

        // AJAX Handlers
        add_action( 'wp_ajax_visa_get_price', array( $this, 'ajax_get_price' ) );
        add_action( 'wp_ajax_nopriv_visa_get_price', array( $this, 'ajax_get_price' ) );
        
        add_action( 'wp_ajax_visa_upload_file', array( $this, 'ajax_upload_file' ) );
        add_action( 'wp_ajax_nopriv_visa_upload_file', array( $this, 'ajax_upload_file' ) );
        
        add_action( 'wp_ajax_visa_checkout', array( $this, 'ajax_checkout' ) );
        add_action( 'wp_ajax_nopriv_visa_checkout', array( $this, 'ajax_checkout' ) );

        // Woo Hooks
        add_filter( 'woocommerce_checkout_fields', array( $this, 'clean_checkout_fields' ) );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_order_meta' ), 10, 4 );
    }

    public function enqueue_assets() {
        wp_enqueue_script( 'jquery' );
        
        // CSS Style khớp với Theme Contact Section
        wp_add_inline_style( 'wp-block-library', '
            /* --- LAYOUT THEME --- */
            .visa-wizard-container { 
                background: #fff; 
                margin: 0 auto;
                max-width: 100%;
                font-family: inherit; /* Kế thừa font của theme */
            }

            /* Sticky Header Style */
            .visa-sticky-header {
                background: #f4f5f8;
                padding: 15px 20px;
                border-bottom: 2px solid #ffaa17; /* Màu cam của theme */
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                position: sticky; 
                top: 0; 
                z-index: 100;
                margin-bottom: 20px;
                border-radius: 5px 5px 0 0;
            }
            .visa-header-title { font-weight: 700; font-size: 18px; color: #222; text-transform: uppercase; }
            .visa-total-price { font-size: 20px; color: #ffaa17; font-weight: 800; }

            /* Steps Visibility */
            #visa_wizard .step-content { 
                display: none !important; 
            }
            #visa_wizard .step-content.active { 
                display: block !important; 
                animation: fadeIn 0.4s ease;
            }
            @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

            /* Progress Bar */
            .visa-progress-container { background: #e5e5e5; height: 5px; width: 100%; margin-bottom: 25px; border-radius: 5px; overflow: hidden; }
            .visa-progress-bar { background: #ffaa17; height: 100%; width: 0%; transition: width 0.4s ease; }
            .visa-step-info { text-align: center; font-size: 14px; font-weight: 600; color: #848484; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }

            /* --- FORM STYLES (Based on Theme) --- */
            /* Input & Select */
            .visa-wizard-container select,
            .visa-wizard-container input[type="text"],
            .visa-wizard-container input[type="email"],
            .visa-wizard-container input[type="tel"],
            .visa-wizard-container input[type="date"] {
                position: relative;
                display: block;
                width: 100%;
                height: 60px;
                padding: 10px 20px;
                font-size: 16px;
                color: #848484;
                background: #f4f5f8;
                border: 1px solid transparent;
                transition: all 500ms ease;
                border-radius: 5px;
                box-shadow: none;
                margin-bottom: 0;
            }

            .visa-wizard-container select:focus,
            .visa-wizard-container input:focus {
                border-color: #ffaa17;
                background: #ffffff;
                outline: none;
            }

            /* Form Group & Labels */
            .visa-wizard-container .form-group {
                margin-bottom: 20px;
                position: relative;
            }
            
            .visa-wizard-container .form-label {
                display: block;
                font-weight: 600;
                margin-bottom: 8px;
                color: #222;
                font-size: 15px;
            }

            .form-desc { font-size: 13px; color: #999; margin-top: 6px; font-style: italic; }

            /* Upload Buttons custom style */
            .file-upload-wrapper {
                position: relative;
                overflow: hidden;
                display: inline-block;
                width: 100%;
            }
            .file-upload-wrapper input[type=file] {
                padding-top: 15px; /* Căn chỉnh text file input */
            }

            /* Preview Images */
            .upload-preview-box { margin-top: 15px; display: none; text-align: center; }
            .upload-preview-box img { max-height: 150px; border-radius: 5px; border: 2px solid #f4f5f8; padding: 5px; background: #fff; }
            .upload-status { font-size: 14px; margin-top: 5px; font-weight: 600; }

            /* Phone Group */
            .phone-group { display: flex; gap: 15px; }
            .phone-code-wrap { width: 35%; }
            .phone-number-wrap { width: 65%; }

            /* Review Box */
            .review-box { background: #f9f9f9; padding: 25px; border-radius: 5px; border: 1px solid #eee; }
            .review-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; border-bottom: 1px dashed #e0e0e0; padding-bottom: 8px; }
            .review-label { color: #848484; }
            .review-value { font-weight: 700; color: #222; text-align: right; }

            /* --- BUTTONS (btn-1 style) --- */
            .visa-actions { 
                margin-top: 30px; 
                display: flex; 
                justify-content: space-between; 
                border-top: 1px solid #eee;
                padding-top: 30px;
            }

            .visa-wizard-container .btn-1 {
                position: relative;
                padding: 15px 40px;
                font-weight: 700;
                text-transform: uppercase;
                color: #fff;
                background: #ffaa17;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                border-radius: 5px;
                font-size: 14px;
                line-height: 24px;
            }

            .visa-wizard-container .btn-1:hover {
                background: #222;
                color: #fff;
            }

            .visa-wizard-container .btn-back {
                background: #e5e5e5;
                color: #555;
            }
            .visa-wizard-container .btn-back:hover {
                background: #ccc;
                color: #333;
            }

            /* Checkbox custom */
            .term-check input { width: auto !important; height: auto !important; display: inline-block !important; margin-right: 10px; }
            
            .error-message { 
                background: #fff3cd; 
                color: #856404; 
                padding: 15px; 
                margin-bottom: 20px; 
                border: 1px solid #ffeeba; 
                border-radius: 5px; 
                display: none; 
            }
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

        $nationalities = ['Vietnam', 'United States', 'United Kingdom', 'Australia', 'Canada', 'France', 'Germany', 'Japan', 'South Korea', 'India', 'China'];
        $phone_codes = [
            '+84' => 'Vietnam (+84)', '+1' => 'USA/Canada (+1)', '+44' => 'UK (+44)', 
            '+61' => 'Australia (+61)', '+33' => 'France (+33)', '+49' => 'Germany (+49)', 
            '+81' => 'Japan (+81)', '+82' => 'South Korea (+82)'
        ];
        
        ob_start();
        ?>
        <div class="visa-wizard-container contact-section" id="visa_wizard">
            
            <div class="form-inner">
                <div class="visa-sticky-header">
                    <div class="visa-header-title">Apply For Visa</div>
                    <div class="visa-total-price" id="header_price_display">--</div>
                </div>

                <div class="visa-step-info">Step <span id="current_step_num">1</span> of 7</div>
                <div class="visa-progress-container">
                    <div class="visa-progress-bar" id="progress_bar"></div>
                </div>

                <div id="global_error" class="error-message">Please fill in all required fields.</div>

                <form id="visa_form">
                    <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                    <input type="hidden" name="variation_id" id="variation_id">
                    <input type="hidden" name="attr_slug_type" value="<?php echo esc_attr($slug_type); ?>">
                    <input type="hidden" name="attr_slug_time" value="<?php echo esc_attr($slug_time); ?>">

                    <div class="step-content active" data-step="1">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">1. Where are you from?</h3>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label class="form-label">Nationality *</label>
                                <span class="wpcf7-form-control-wrap">
                                    <select name="nationality" class="form-control required-field">
                                        <option value="">-- Select Country --</option>
                                        <?php foreach($nationalities as $n) echo "<option value='$n'>$n</option>"; ?>
                                    </select>
                                </span>
                                <p class="form-desc">Select the nationality on your passport.</p>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="2">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">2. Select Visa Type</h3>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label class="form-label">Visa Type *</label>
                                <select name="visa_type" class="form-control price-trigger required-field">
                                    <option value="">-- Select Option --</option>
                                    <?php if(isset($attributes[$slug_type])): foreach($attributes[$slug_type] as $term): ?>
                                        <option value="<?php echo esc_attr($term); ?>"><?php echo esc_html($term); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="3">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">3. Processing Time</h3>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label class="form-label">Processing Time *</label>
                                <select name="processing_time" class="form-control price-trigger required-field">
                                    <option value="">-- Select Option --</option>
                                    <?php if(isset($attributes[$slug_time])): foreach($attributes[$slug_time] as $term): ?>
                                        <option value="<?php echo esc_attr($term); ?>"><?php echo esc_html($term); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <p class="form-desc" style="color:#ffaa17;">Note: Working hours 8:30AM - 4:30PM (Mon-Fri).</p>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="4">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">4. Date of Arrival</h3>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label class="form-label">Arrival Date *</label>
                                <input type="date" name="arrival_date" class="form-control required-field">
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="5">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">5. Documents Upload</h3>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <label class="form-label">Passport Photo *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="file_passport" accept="image/*" class="form-control">
                                    <input type="hidden" name="passport_url" id="passport_url" class="required-field">
                                </div>
                                <div id="stat_passport" class="upload-status"></div>
                                <div class="upload-preview-box" id="prev_passport"></div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <label class="form-label">Portrait Photo *</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" id="file_photo" accept="image/*" class="form-control">
                                    <input type="hidden" name="photo_url" id="photo_url" class="required-field">
                                </div>
                                <div id="stat_photo" class="upload-status"></div>
                                <div class="upload-preview-box" id="prev_photo"></div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="6">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">6. Contact Details</h3>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="fullname" class="form-control required-field" placeholder="Enter full name">
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control required-field" placeholder="name@example.com">
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <label class="form-label">Phone Number *</label>
                                <div class="phone-group">
                                    <div class="phone-code-wrap">
                                        <select name="phone_code" class="form-control">
                                            <?php foreach($phone_codes as $code => $label) echo "<option value='$code'>$label</option>"; ?>
                                        </select>
                                    </div>
                                    <div class="phone-number-wrap">
                                        <input type="tel" name="phone_number" class="form-control required-field" placeholder="Phone Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content" data-step="7">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                <h3 class="step-title">7. Review & Pay</h3>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="review-box">
                                    <div class="review-item"><span>Nationality:</span> <span class="review-value" id="rev_nation">--</span></div>
                                    <div class="review-item"><span>Visa Type:</span> <span class="review-value" id="rev_type">--</span></div>
                                    <div class="review-item"><span>Time:</span> <span class="review-value" id="rev_time">--</span></div>
                                    <div class="review-item"><span>Arrival:</span> <span class="review-value" id="rev_date">--</span></div>
                                    <div class="review-item"><span>Name:</span> <span class="review-value" id="rev_name">--</span></div>
                                    <div class="review-item"><span>Email:</span> <span class="review-value" id="rev_email">--</span></div>
                                    <div class="review-item" style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
                                        <span style="font-weight:bold; font-size:18px;">Total:</span> 
                                        <span class="review-value" id="rev_price" style="color:#ffaa17; font-size:22px;">--</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 form-group term-check" style="margin-top: 25px;">
                                <label style="cursor:pointer; display: flex; align-items: center;">
                                    <input type="checkbox" id="agree_terms"> 
                                    <span style="font-size: 14px; color: #555;">
                                        I acknowledge that I have read and accept the Terms of Service.
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
            let currentStep = 1;
            const totalSteps = 7;

            // FIX VISIBILITY
            $('.step-content').hide();
            $('.step-content[data-step="1"]').show();

            // NAVIGATION
            function showStep(step) {
                $('#global_error').hide();
                $('.step-content').removeClass('active').hide();
                $('.step-content[data-step="'+step+'"]').fadeIn(300).addClass('active');
                
                $('#current_step_num').text(step);
                $('#progress_bar').css('width', (step/totalSteps)*100 + '%');

                if(step === 1) $('#btn_back').hide(); else $('#btn_back').show();
                
                if(step === totalSteps) {
                    $('#btn_next').hide();
                    $('#btn_submit').show();
                    populateReview();
                } else {
                    $('#btn_next').show();
                    $('#btn_submit').hide();
                }
            }

            function validateStep(step) {
                let isValid = true;
                let currentPanel = $('.step-content[data-step="'+step+'"]');
                
                currentPanel.find('.required-field').each(function(){
                    if($(this).val() === '') {
                        isValid = false;
                        $(this).css('border-color', '#ff3b30');
                    } else {
                        $(this).css('border-color', 'transparent'); // Reset to style theme
                    }
                });

                if(!isValid) {
                    $('#global_error').slideDown();
                }
                return isValid;
            }

            $('#btn_next').click(function(e){
                e.preventDefault();
                if(validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            $('#btn_back').click(function(e){
                e.preventDefault();
                currentStep--;
                showStep(currentStep);
            });

            // GET PRICE
            $('.price-trigger').change(function(){
                let type = $('select[name="visa_type"]').val();
                let time = $('select[name="processing_time"]').val();
                
                if(type && time) {
                    $('#header_price_display').css('opacity', '0.5');
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                        action: 'visa_get_price',
                        product_id: $('input[name="product_id"]').val(),
                        type: type,
                        time: time,
                        slug_type: $('input[name="attr_slug_type"]').val(),
                        slug_time: $('input[name="attr_slug_time"]').val()
                    }, function(res){
                        $('#header_price_display').css('opacity', '1');
                        if(res.success) {
                            $('#header_price_display').text(res.data.price_html);
                            $('#variation_id').val(res.data.variation_id);
                        } else {
                            $('#header_price_display').text('--');
                        }
                    });
                }
            });

            // UPLOAD
            function setupUpload(id, hidden_id, msg_id, prev_id) {
                $(id).change(function(){
                    let fd = new FormData();
                    fd.append('file', this.files[0]);
                    fd.append('action', 'visa_upload_file');
                    $(msg_id).text('Uploading...').css('color','#ffaa17');
                    
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST', contentType: false, processData: false, data: fd,
                        success: function(res){
                            if(res.success){
                                $(hidden_id).val(res.data.url);
                                $(msg_id).text('Success').css('color','green');
                                $(prev_id).html('<img src="'+res.data.url+'">').fadeIn();
                                $(hidden_id).closest('.file-upload-wrapper').find('input').css('border-color', 'green');
                            } else {
                                $(msg_id).text('Error').css('color','red');
                            }
                        }
                    });
                });
            }
            setupUpload('#file_passport', '#passport_url', '#stat_passport', '#prev_passport');
            setupUpload('#file_photo', '#photo_url', '#stat_photo', '#prev_photo');

            // POPULATE REVIEW
            function populateReview() {
                $('#rev_nation').text($('select[name="nationality"]').val());
                $('#rev_type').text($('select[name="visa_type"]').val());
                $('#rev_time').text($('select[name="processing_time"]').val());
                $('#rev_date').text($('input[name="arrival_date"]').val());
                $('#rev_name').text($('input[name="fullname"]').val());
                $('#rev_email').text($('input[name="email"]').val());
                $('#rev_price').text($('#header_price_display').text());
            }

            // CHECKOUT
            $('#btn_submit').click(function(e){
                e.preventDefault();
                if(!$('#agree_terms').is(':checked')) {
                    alert('Please accept terms.'); return;
                }

                let btn = $(this);
                btn.text('Processing...').prop('disabled', true);

                $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                    action: 'visa_checkout',
                    data: $('#visa_form').serialize()
                }, function(res){
                    if(res.success) {
                        window.location.href = res.data.redirect;
                    } else {
                        alert(res.data.message);
                        btn.text('PAY NOW').prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    /* --- BACKEND --- */

    public function ajax_get_price() {
        $pid = intval($_POST['product_id']);
        $type = sanitize_text_field($_POST['type']);
        $time = sanitize_text_field($_POST['time']);
        $slug_type = sanitize_text_field($_POST['slug_type']);
        $slug_time = sanitize_text_field($_POST['slug_time']);

        $match_attributes = [
            'attribute_' . $slug_type => $type,
            'attribute_' . $slug_time => $time
        ];

        $data_store = WC_Data_Store::load( 'product' );
        $vid = $data_store->find_matching_product_variation( new WC_Product($pid), $match_attributes );

        if($vid) {
            $v = wc_get_product($vid);
            wp_send_json_success(['variation_id'=>$vid, 'price_html'=>strip_tags(wc_price($v->get_price()))]);
        }
        wp_send_json_error();
    }

    public function ajax_upload_file() {
        if(!function_exists('wp_handle_upload')) require_once(ABSPATH.'wp-admin/includes/file.php');
        $up = wp_handle_upload($_FILES['file'], ['test_form'=>false]);
        if(isset($up['url'])) wp_send_json_success(['url'=>$up['url']]);
        else wp_send_json_error();
    }

    public function ajax_checkout() {
        parse_str($_POST['data'], $form);
        WC()->cart->empty_cart();

        $full_phone = $form['phone_code'] . ' ' . $form['phone_number'];

        $custom_data = [
            'visa_full_info' => [
                'nationality' => $form['nationality'],
                'arrival' => $form['arrival_date'],
                'passport' => $form['passport_url'],
                'photo' => $form['photo_url'],
                'fullname' => $form['fullname'],
                'email' => $form['email'],
                'phone' => $full_phone
            ]
        ];

        $added = WC()->cart->add_to_cart( $form['product_id'], 1, $form['variation_id'], [], $custom_data );

        if($added) {
            $c = WC()->customer;
            $c->set_billing_first_name($form['fullname']);
            $c->set_billing_email($form['email']);
            $c->set_billing_phone($full_phone);
            
            $c->set_billing_country('VN'); 
            $c->set_billing_address_1('Online Application');
            $c->set_billing_city('Hanoi');
            $c->set_billing_postcode('10000');
            $c->save();
            
            wp_send_json_success(['redirect' => wc_get_checkout_url()]);
        } else {
            wp_send_json_error(['message' => 'Cannot add to cart.']);
        }
    }

    public function save_order_meta($item, $key, $values, $order) {
        if(isset($values['visa_full_info'])) {
            $d = $values['visa_full_info'];
            $item->add_meta_data('Nationality', $d['nationality']);
            $item->add_meta_data('Arrival Date', $d['arrival']);
            $item->add_meta_data('Passport Link', $d['passport']);
            $item->add_meta_data('Photo Link', $d['photo']);
        }
    }

    public function clean_checkout_fields($fields) {
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);
        unset($fields['shipping']);
        return $fields;
    }
}

new Visa_Wizard_Themed();