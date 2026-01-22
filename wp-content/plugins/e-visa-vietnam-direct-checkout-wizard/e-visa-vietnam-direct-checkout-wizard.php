<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Multi-step E-Visa Vietnam Booking Form with Direct Checkout and Auto-Clean Session.
Version: 1.0
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Wizard_V2 {

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
        wp_add_inline_style( 'wp-block-library', '
            /* --- LAYOUT & CONTAINER --- */
            .visa-wizard-container { max-width: 800px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; position: relative; }
            
            /* --- STICKY HEADER (PRICE) --- */
            .visa-sticky-header { background: #fff; padding: 20px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
            .visa-header-title { font-weight: 700; font-size: 18px; color: #2c3e50; }
            .visa-total-price { font-size: 20px; color: #e74c3c; font-weight: 800; }
            .visa-calculating { font-size: 14px; color: #95a5a6; font-weight: normal; }

            /* --- PROGRESS BAR --- */
            .visa-progress-container { background: #f0f2f5; height: 6px; width: 100%; }
            .visa-progress-bar { background: #3498db; height: 100%; width: 14%; transition: width 0.4s ease; } /* 100% / 7 steps approx 14% */
            .visa-step-info { padding: 10px 30px; font-size: 13px; color: #7f8c8d; font-weight: 600; background: #f9f9f9; border-bottom: 1px solid #eee; }

            /* --- STEPS CONTENT --- */
            .step-content { padding: 40px 30px; display: none; }
            .step-content.active { display: block; animation: slideIn 0.3s ease-out; }
            @keyframes slideIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
            
            h3.step-title { margin-top: 0; margin-bottom: 25px; color: #2c3e50; font-size: 22px; }

            /* --- FORM FIELDS --- */
            .form-group { margin-bottom: 25px; }
            .form-label { display: block; font-weight: 600; margin-bottom: 10px; color: #34495e; }
            .form-control { width: 100%; padding: 14px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box; font-size: 16px; transition: border 0.3s; }
            .form-control:focus { border-color: #3498db; outline: none; }
            .form-desc { font-size: 0.9em; color: #95a5a6; margin-top: 8px; font-style: italic; }

            /* --- PHONE INPUT GROUP --- */
            .phone-group { display: flex; gap: 10px; }
            .phone-code { width: 35% !important; }
            .phone-number { width: 65% !important; }

            /* --- UPLOAD PREVIEW --- */
            .upload-preview-box { margin-top: 15px; display: none; }
            .upload-preview-box img { max-width: 150px; border-radius: 6px; border: 1px solid #ddd; padding: 3px; }
            .upload-status { font-size: 0.9em; margin-top: 5px; font-weight: 600; }

            /* --- REVIEW BOX --- */
            .review-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; }
            .review-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; border-bottom: 1px dashed #e0e0e0; padding-bottom: 8px; }
            .review-item:last-child { border-bottom: none; }
            .review-label { color: #7f8c8d; }
            .review-value { font-weight: 600; color: #2c3e50; text-align: right; }

            /* --- BUTTONS --- */
            .visa-actions { padding: 20px 30px; border-top: 1px solid #eee; display: flex; justify-content: space-between; background: #fff; }
            .btn-visa { padding: 14px 30px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.2s; }
            .btn-next { background: #3498db; color: white; box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3); }
            .btn-next:hover { background: #2980b9; transform: translateY(-1px); }
            .btn-back { background: #ecf0f1; color: #7f8c8d; }
            .btn-back:hover { background: #bdc3c7; }
            .btn-checkout { background: #27ae60; color: white; box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3); }
            .btn-checkout:hover { background: #219150; transform: translateY(-1px); }
            
            /* --- ERROR --- */
            .error-message { color: #e74c3c; font-size: 14px; margin-top: 10px; display: none; background: #fce4e4; padding: 10px; border-radius: 4px; border-left: 3px solid #e74c3c; }
        ' );
    }

    public function render_wizard( $atts ) {
        $atts = shortcode_atts( ['product_id' => 0], $atts );
        $pid = intval( $atts['product_id'] );
        $product = wc_get_product( $pid );

        if ( ! $product || ! $product->is_type( 'variable' ) ) return '<p style="color:red; font-weight:bold;">ERROR: Invalid Product ID.</p>';

        // AUTO-DETECT SLUGS
        $attributes = $product->get_variation_attributes();
        $attr_keys = array_keys( $attributes );
        $slug_type = ''; $slug_time = '';
        foreach($attr_keys as $key) {
            if(strpos($key, 'type') !== false || strpos($key, 'loai') !== false) $slug_type = $key;
            if(strpos($key, 'time') !== false || strpos($key, 'tgian') !== false || strpos($key, 'processing') !== false) $slug_time = $key;
        }
        if(empty($slug_type) && isset($attr_keys[0])) $slug_type = $attr_keys[0];
        if(empty($slug_time) && isset($attr_keys[1])) $slug_time = $attr_keys[1];

        // DATA ARRAYS
        $nationalities = ['Vietnam', 'United States', 'United Kingdom', 'Australia', 'Canada', 'France', 'Germany', 'Japan', 'South Korea', 'India', 'China'];
        $phone_codes = [
            '+84' => 'Vietnam (+84)', '+1' => 'USA/Canada (+1)', '+44' => 'UK (+44)', 
            '+61' => 'Australia (+61)', '+33' => 'France (+33)', '+49' => 'Germany (+49)', 
            '+81' => 'Japan (+81)', '+82' => 'South Korea (+82)'
        ];
        
        ob_start();
        ?>
        <div class="visa-wizard-container" id="visa_wizard">
            
            <div class="visa-sticky-header">
                <div class="visa-header-title">Apply For Visa</div>
                <div class="visa-total-price" id="header_price_display">--</div>
            </div>

            <div class="visa-progress-container">
                <div class="visa-progress-bar" id="progress_bar"></div>
            </div>
            <div class="visa-step-info">Step <span id="current_step_num">1</span> of 7</div>

            <form id="visa_form">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <input type="hidden" name="variation_id" id="variation_id">
                <input type="hidden" name="attr_slug_type" value="<?php echo esc_attr($slug_type); ?>">
                <input type="hidden" name="attr_slug_time" value="<?php echo esc_attr($slug_time); ?>">

                <div id="global_error" class="error-message">Please fill in all required fields.</div>

                <div class="step-content active" data-step="1">
                    <h3 class="step-title">Where are you from?</h3>
                    <div class="form-group">
                        <label class="form-label">Nationality *</label>
                        <select name="nationality" class="form-control required-field">
                            <option value="">-- Select Country --</option>
                            <?php foreach($nationalities as $n) echo "<option value='$n'>$n</option>"; ?>
                        </select>
                        <div class="form-desc">A drop-down list of nationalities that we can accept bookings.</div>
                    </div>
                </div>

                <div class="step-content" data-step="2">
                    <h3 class="step-title">Select Visa Type</h3>
                    <div class="form-group">
                        <label class="form-label">Visa Type *</label>
                        <select name="visa_type" class="form-control price-trigger required-field">
                            <option value="">-- Select Option --</option>
                            <?php if(isset($attributes[$slug_type])): foreach($attributes[$slug_type] as $term): ?>
                                <option value="<?php echo esc_attr($term); ?>"><?php echo esc_html($term); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>

                <div class="step-content" data-step="3">
                    <h3 class="step-title">Processing Time</h3>
                    <div class="form-group">
                        <label class="form-label">Processing Time *</label>
                        <select name="processing_time" class="form-control price-trigger required-field">
                            <option value="">-- Select Option --</option>
                            <?php if(isset($attributes[$slug_time])): foreach($attributes[$slug_time] as $term): ?>
                                <option value="<?php echo esc_attr($term); ?>"><?php echo esc_html($term); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <div class="form-desc" style="color:#e67e22;">Note: Processing time counts from the time the application is confirmed (8:30AM - 4:30PM Mon-Fri).</div>
                    </div>
                </div>

                <div class="step-content" data-step="4">
                    <h3 class="step-title">Date of Arrival</h3>
                    <div class="form-group">
                        <label class="form-label">Arrival Date *</label>
                        <input type="date" name="arrival_date" class="form-control required-field">
                    </div>
                </div>

                <div class="step-content" data-step="5">
                    <h3 class="step-title">Documents</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Passport Photo *</label>
                        <input type="file" id="file_passport" accept="image/*" class="form-control">
                        <input type="hidden" name="passport_url" id="passport_url" class="required-field">
                        <div id="stat_passport" class="upload-status"></div>
                        <div class="upload-preview-box" id="prev_passport"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Portrait Photo *</label>
                        <input type="file" id="file_photo" accept="image/*" class="form-control">
                        <input type="hidden" name="photo_url" id="photo_url" class="required-field">
                        <div id="stat_photo" class="upload-status"></div>
                        <div class="upload-preview-box" id="prev_photo"></div>
                    </div>
                </div>

                <div class="step-content" data-step="6">
                    <h3 class="step-title">Contact Details</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="fullname" class="form-control required-field" placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control required-field" placeholder="name@example.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <div class="phone-group">
                            <select name="phone_code" class="form-control phone-code">
                                <?php foreach($phone_codes as $code => $label) echo "<option value='$code'>$label</option>"; ?>
                            </select>
                            <input type="tel" name="phone_number" class="form-control phone-number required-field" placeholder="123 456 789">
                        </div>
                    </div>
                </div>

                <div class="step-content" data-step="7">
                    <h3 class="step-title">Review & Pay</h3>
                    
                    <div class="review-box">
                        <div class="review-item"><span>Nationality:</span> <span class="review-value" id="rev_nation">--</span></div>
                        <div class="review-item"><span>Visa Type:</span> <span class="review-value" id="rev_type">--</span></div>
                        <div class="review-item"><span>Time:</span> <span class="review-value" id="rev_time">--</span></div>
                        <div class="review-item"><span>Arrival:</span> <span class="review-value" id="rev_date">--</span></div>
                        <div class="review-item"><span>Name:</span> <span class="review-value" id="rev_name">--</span></div>
                        <div class="review-item"><span>Email:</span> <span class="review-value" id="rev_email">--</span></div>
                        <div class="review-item" style="border-top: 1px solid #ccc; padding-top: 10px; margin-top: 10px;">
                            <span style="font-weight:bold;">Total:</span> 
                            <span class="review-value" id="rev_price" style="color:#e74c3c; font-size:1.2em;">--</span>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label style="cursor:pointer; display: flex; gap: 10px; align-items: flex-start;">
                            <input type="checkbox" id="agree_terms" style="margin-top: 4px;"> 
                            <span style="font-size: 0.9em; color: #555;">
                                By submitting payment, I acknowledge that I have read and accept the EVISAS VIETNAM Terms of Service, Privacy Policy, and Refund Policy.
                            </span>
                        </label>
                    </div>
                </div>

                <div class="visa-actions">
                    <button type="button" class="btn-visa btn-back" id="btn_back" style="display:none;">Back</button>
                    <div style="flex:1;"></div>
                    <button type="button" class="btn-visa btn-next" id="btn_next">Next Step</button>
                    <button type="button" class="btn-visa btn-checkout" id="btn_submit" style="display:none;">PAY NOW</button>
                </div>
            </form>
        </div>

        <script>
        jQuery(document).ready(function($){
            let currentStep = 1;
            const totalSteps = 7;

            // --- 1. NAVIGATION & VALIDATION ---
            function showStep(step) {
                $('.error-message').hide();
                $('.step-content').removeClass('active');
                $('.step-content[data-step="'+step+'"]').addClass('active');
                
                // Update Progress
                $('#current_step_num').text(step);
                $('#progress_bar').css('width', (step/totalSteps)*100 + '%');

                // Buttons Visibility
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

            // Check if current step fields are filled
            function validateStep(step) {
                let isValid = true;
                let currentPanel = $('.step-content[data-step="'+step+'"]');
                
                currentPanel.find('.required-field').each(function(){
                    if($(this).val() === '') {
                        isValid = false;
                        $(this).css('border-color', '#e74c3c');
                    } else {
                        $(this).css('border-color', '#dfe6e9');
                    }
                });

                if(!isValid) {
                    $('#global_error').text('Please fill in all required fields marked with *').slideDown();
                }
                return isValid;
            }

            $('#btn_next').click(function(){
                if(validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            $('#btn_back').click(function(){
                currentStep--;
                showStep(currentStep);
            });

            // --- 2. PRICE CALCULATION ---
            $('.price-trigger').change(function(){
                let type = $('select[name="visa_type"]').val();
                let time = $('select[name="processing_time"]').val();
                
                if(type && time) {
                    $('#header_price_display').html('<span class="visa-calculating">Calculating...</span>');
                    
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                        action: 'visa_get_price',
                        product_id: $('input[name="product_id"]').val(),
                        type: type,
                        time: time,
                        slug_type: $('input[name="attr_slug_type"]').val(),
                        slug_time: $('input[name="attr_slug_time"]').val()
                    }, function(res){
                        if(res.success) {
                            $('#header_price_display').text(res.data.price_html);
                            $('#variation_id').val(res.data.variation_id);
                        } else {
                            $('#header_price_display').text('--');
                        }
                    });
                }
            });

            // --- 3. FILE UPLOAD & PREVIEW ---
            function setupUpload(id, hidden_id, msg_id, prev_id) {
                $(id).change(function(){
                    let fd = new FormData();
                    fd.append('file', this.files[0]);
                    fd.append('action', 'visa_upload_file');
                    $(msg_id).text('Uploading...').css('color','blue');
                    
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST', contentType: false, processData: false, data: fd,
                        success: function(res){
                            if(res.success){
                                $(hidden_id).val(res.data.url);
                                $(msg_id).text('Uploaded successfully').css('color','green');
                                // Show Preview
                                $(prev_id).html('<img src="'+res.data.url+'">').fadeIn();
                                $(hidden_id).css('border-color', '#27ae60'); // Valid visual
                            } else {
                                $(msg_id).text('Error uploading file').css('color','red');
                            }
                        }
                    });
                });
            }
            setupUpload('#file_passport', '#passport_url', '#stat_passport', '#prev_passport');
            setupUpload('#file_photo', '#photo_url', '#stat_photo', '#prev_photo');

            // --- 4. REVIEW DATA ---
            function populateReview() {
                $('#rev_nation').text($('select[name="nationality"]').val());
                $('#rev_type').text($('select[name="visa_type"]').val());
                $('#rev_time').text($('select[name="processing_time"]').val());
                $('#rev_date').text($('input[name="arrival_date"]').val());
                $('#rev_name').text($('input[name="fullname"]').val());
                $('#rev_email').text($('input[name="email"]').val());
                $('#rev_price').text($('#header_price_display').text());
            }

            // --- 5. CHECKOUT ---
            $('#btn_submit').click(function(){
                if(!$('#agree_terms').is(':checked')) {
                    $('#global_error').text('You must agree to the Terms of Service.').slideDown(); return;
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

    /* --- BACKEND HANDLERS --- */

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
            
            // Dummy data to pass validation if needed
            $c->set_billing_country('VN'); 
            $c->set_billing_address_1('Online Application');
            $c->set_billing_city('Hanoi');
            $c->set_billing_postcode('10000');
            $c->save();
            
            wp_send_json_success(['redirect' => wc_get_checkout_url()]);
        } else {
            wp_send_json_error(['message' => 'Cannot add to cart. Please try again.']);
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

new Visa_Wizard_V2();