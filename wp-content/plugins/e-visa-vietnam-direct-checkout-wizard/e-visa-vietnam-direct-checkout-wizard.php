<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Multi-step E-Visa Vietnam Booking Form with Direct Checkout and Auto-Clean Session.
Version: 1.0
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Five_Step_Wizard {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'visa_5step_form', array( $this, 'render_wizard' ) );

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
            /* Container & Layout */
            .visa-wizard-container { max-width: 800px; margin: 20px auto; background: #fff; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); overflow: hidden; font-family: sans-serif; }
            
            /* Sticky Price Header */
            .visa-sticky-header { background: #f8f9fa; padding: 15px 20px; border-bottom: 2px solid #007cba; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; }
            .visa-header-title { font-weight: bold; font-size: 16px; color: #333; }
            .visa-total-price { font-size: 18px; color: #d63638; font-weight: 800; }

            /* Progress Bar */
            .visa-progress { display: flex; background: #eee; height: 4px; width: 100%; }
            .visa-progress-bar { background: #007cba; height: 100%; width: 20%; transition: width 0.3s ease; }
            .visa-step-indicator { padding: 10px; text-align: center; font-size: 12px; color: #666; background: #fafafa; border-bottom: 1px solid #eee; }

            /* Steps Content */
            .step-content { padding: 30px; display: none; animation: fadeIn 0.4s; }
            .step-content.active { display: block; }
            @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

            /* Form Elements */
            .form-group { margin-bottom: 20px; }
            .form-label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
            .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
            .form-desc { font-size: 0.85em; color: #888; margin-top: 5px; font-style: italic; }

            /* Radio Selection Style (Better UX) */
            .radio-group { display: flex; flex-direction: column; gap: 10px; }
            .radio-option { border: 1px solid #ddd; padding: 12px; border-radius: 4px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; }
            .radio-option:hover { background: #f0f7fb; border-color: #007cba; }
            .radio-option input { margin-right: 10px; transform: scale(1.2); }
            .radio-option.selected { background: #e6f2f9; border-color: #007cba; font-weight: bold; }

            /* Review Box */
            .review-box { background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 15px; border: 1px dashed #ccc; }
            .review-item { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95em; }
            .review-label { color: #666; }
            .review-value { font-weight: 600; }

            /* Buttons */
            .visa-actions { padding: 20px 30px; border-top: 1px solid #eee; display: flex; justify-content: space-between; background: #fff; }
            .btn-visa { padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600; transition: 0.2s; }
            .btn-next { background: #007cba; color: white; }
            .btn-next:hover { background: #005a87; }
            .btn-back { background: #e9ecef; color: #333; }
            .btn-back:hover { background: #dde2e6; }
            .btn-checkout { background: #28a745; color: white; }
            .btn-checkout:disabled { background: #ccc; cursor: not-allowed; }

            .error-text { color: red; font-size: 0.9em; margin-top: 5px; display: none; }
            .upload-status { font-size: 0.85em; margin-top: 5px; }
        ' );
    }

    public function render_wizard( $atts ) {
        $atts = shortcode_atts( ['product_id' => 0], $atts );
        $pid = intval( $atts['product_id'] );
        $product = wc_get_product( $pid );

        if ( ! $product || ! $product->is_type( 'variable' ) ) return '<p style="color:red; font-weight:bold;">LỖI: ID sản phẩm không hợp lệ hoặc không phải Variable Product.</p>';

        // TỰ ĐỘNG DÒ TÌM SLUG (FIX LỖI KHÔNG HIỆN)
        $attributes = $product->get_variation_attributes();
        $attr_keys = array_keys( $attributes );
        // Giả định: Attribute đầu tiên là Type, thứ 2 là Time (theo thứ tự tạo trong Woo)
        // Hoặc tìm theo từ khóa 'visa', 'type', 'time'
        $slug_type = ''; 
        $slug_time = '';
        
        foreach($attr_keys as $key) {
            if(strpos($key, 'type') !== false || strpos($key, 'loai') !== false) $slug_type = $key;
            if(strpos($key, 'time') !== false || strpos($key, 'tgian') !== false || strpos($key, 'processing') !== false) $slug_time = $key;
        }
        // Fallback nếu không tìm thấy (lấy theo thứ tự)
        if(empty($slug_type) && isset($attr_keys[0])) $slug_type = $attr_keys[0];
        if(empty($slug_time) && isset($attr_keys[1])) $slug_time = $attr_keys[1];

        $nationalities = ['Vietnam', 'United States', 'United Kingdom', 'Australia', 'Canada', 'France', 'Germany', 'Japan', 'South Korea', 'India', 'China'];
        
        ob_start();
        ?>
        <div class="visa-wizard-container" id="visa_wizard">
            
            <div class="visa-sticky-header">
                <div class="visa-header-title">Vietnam E-Visa Application</div>
                <div class="visa-total-price" id="header_price_display">--</div>
            </div>

            <div class="visa-step-indicator">Step <span id="current_step_num">1</span> of 5</div>
            <div class="visa-progress">
                <div class="visa-progress-bar" id="progress_bar"></div>
            </div>

            <form id="visa_form">
                <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                <input type="hidden" name="variation_id" id="variation_id">
                <input type="hidden" name="attr_slug_type" value="<?php echo esc_attr($slug_type); ?>">
                <input type="hidden" name="attr_slug_time" value="<?php echo esc_attr($slug_time); ?>">

                <div class="step-content active" data-step="1">
                    <h3>Step 1: Where are you from?</h3>
                    <div class="form-group">
                        <label class="form-label">Select Nationality</label>
                        <select name="nationality" class="form-control" required>
                            <option value="">-- Select Country --</option>
                            <?php foreach($nationalities as $n) echo "<option value='$n'>$n</option>"; ?>
                        </select>
                        <div class="form-desc">Select the nationality on your passport.</div>
                    </div>
                </div>

                <div class="step-content" data-step="2">
                    <h3>Step 2: Select Visa Type</h3>
                    <div class="radio-group">
                        <?php if(isset($attributes[$slug_type])): ?>
                            <?php foreach($attributes[$slug_type] as $term): ?>
                                <label class="radio-option">
                                    <input type="radio" name="visa_type" value="<?php echo esc_attr($term); ?>" class="price-trigger">
                                    <span><?php echo esc_html($term); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: echo "<p style='color:red'>Không tìm thấy thuộc tính Visa Type. Hãy kiểm tra lại sản phẩm.</p>"; endif; ?>
                    </div>
                </div>

                <div class="step-content" data-step="3">
                    <h3>Step 3: Processing Time</h3>
                    <div class="radio-group">
                        <?php if(isset($attributes[$slug_time])): ?>
                            <?php foreach($attributes[$slug_time] as $term): ?>
                                <label class="radio-option">
                                    <input type="radio" name="processing_time" value="<?php echo esc_attr($term); ?>" class="price-trigger">
                                    <span><?php echo esc_html($term); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: echo "<p style='color:red'>Không tìm thấy thuộc tính Processing Time.</p>"; endif; ?>
                    </div>
                    <div class="form-desc" style="margin-top:10px; color:#d63638;">Note: Working hours 8:30AM - 4:30PM (Mon-Fri).</div>
                </div>

                <div class="step-content" data-step="4">
                    <h3>Step 4: Applicant Details</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Date of Arrival</label>
                        <input type="date" name="arrival_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Upload Passport</label>
                        <input type="file" id="file_passport" accept="image/*,.pdf" class="form-control">
                        <input type="hidden" name="passport_url" id="passport_url">
                        <div id="msg_passport" class="upload-status"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Upload Photo</label>
                        <input type="file" id="file_photo" accept="image/*" class="form-control">
                        <input type="hidden" name="photo_url" id="photo_url">
                        <div id="msg_photo" class="upload-status"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" placeholder="As shown on passport">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control">
                    </div>
                </div>

                <div class="step-content" data-step="5">
                    <h3>Step 5: Review & Checkout</h3>
                    
                    <div class="review-box">
                        <div class="review-item"><span class="review-label">Nationality:</span> <span class="review-value" id="rev_nation">--</span></div>
                        <div class="review-item"><span class="review-label">Type:</span> <span class="review-value" id="rev_type">--</span></div>
                        <div class="review-item"><span class="review-label">Time:</span> <span class="review-value" id="rev_time">--</span></div>
                        <div class="review-item"><span class="review-label">Name:</span> <span class="review-value" id="rev_name">--</span></div>
                        <hr style="border:0; border-top:1px dashed #ccc; margin:10px 0;">
                        <div class="review-item"><span class="review-label">Total Price:</span> <span class="review-value" id="rev_price" style="color:#d63638; font-size:1.2em;">--</span></div>
                    </div>

                    <div class="form-group" style="background:#f0f0f0; padding:10px; font-size:0.9em;">
                        <strong>Terms of Service:</strong><br>
                        1. Application fees are non-refundable.<br>
                        2. Processing time is estimated during working hours.<br>
                        <label style="display:block; margin-top:10px; cursor:pointer;">
                            <input type="checkbox" id="agree_terms"> I agree to the Terms & Privacy Policy.
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
            const totalSteps = 5;

            // Highlight radio selection
            $('.radio-option input').change(function(){
                let name = $(this).attr('name');
                $('input[name="'+name+'"]').closest('.radio-option').removeClass('selected');
                $(this).closest('.radio-option').addClass('selected');
            });

            // Navigation
            function showStep(step) {
                $('.step-content').removeClass('active');
                $('.step-content[data-step="'+step+'"]').addClass('active');
                
                // Update Progress
                $('#current_step_num').text(step);
                $('#progress_bar').css('width', (step/totalSteps)*100 + '%');

                // Buttons
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

            $('#btn_next').click(function(){
                if(!validateStep(currentStep)) return;
                currentStep++;
                showStep(currentStep);
            });

            $('#btn_back').click(function(){
                currentStep--;
                showStep(currentStep);
            });

            function validateStep(step) {
                if(step === 1 && !$('select[name="nationality"]').val()) { alert('Please select Nationality'); return false; }
                if(step === 2 && !$('input[name="visa_type"]:checked').val()) { alert('Please select Visa Type'); return false; }
                if(step === 3 && !$('input[name="processing_time"]:checked').val()) { alert('Please select Processing Time'); return false; }
                if(step === 4) {
                    if(!$('#passport_url').val() || !$('#photo_url').val()) { alert('Please upload files.'); return false; }
                    if(!$('input[name="fullname"]').val() || !$('input[name="email"]').val()) { alert('Please fill in required fields.'); return false; }
                }
                return true;
            }

            // Price Calculation (Trigger on Step 2 & 3 Change)
            $('.price-trigger').change(function(){
                let type = $('input[name="visa_type"]:checked').val();
                let time = $('input[name="processing_time"]:checked').val();
                
                if(type && time) {
                    $('#header_price_display').html('<span style="font-size:0.7em; color:#999;">Calculating...</span>');
                    
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
                            $('#header_price_display').text('N/A');
                        }
                    });
                }
            });

            // File Upload
            function setupUpload(id, hidden_id, msg_id) {
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
                                $(msg_id).text('Uploaded ✓').css('color','green');
                            } else {
                                $(msg_id).text('Error').css('color','red');
                            }
                        }
                    });
                });
            }
            setupUpload('#file_passport', '#passport_url', '#msg_passport');
            setupUpload('#file_photo', '#photo_url', '#msg_photo');

            // Populate Review Data
            function populateReview() {
                $('#rev_nation').text($('select[name="nationality"]').val());
                $('#rev_type').text($('input[name="visa_type"]:checked').val());
                $('#rev_time').text($('input[name="processing_time"]:checked').val());
                $('#rev_name').text($('input[name="fullname"]').val());
                $('#rev_price').text($('#header_price_display').text());
            }

            // Final Submit
            $('#btn_submit').click(function(){
                if(!$('#agree_terms').is(':checked')) {
                    alert('You must agree to the Terms of Service to proceed.'); return;
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

    /* BACKEND: Get Price Logic (Fix Slug Issue) */
    public function ajax_get_price() {
        $pid = intval($_POST['product_id']);
        $type = sanitize_text_field($_POST['type']);
        $time = sanitize_text_field($_POST['time']);
        $slug_type = sanitize_text_field($_POST['slug_type']);
        $slug_time = sanitize_text_field($_POST['slug_time']);

        // Map selection to attributes with correct slugs
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

    /* BACKEND: Upload Logic */
    public function ajax_upload_file() {
        if(!function_exists('wp_handle_upload')) require_once(ABSPATH.'wp-admin/includes/file.php');
        $up = wp_handle_upload($_FILES['file'], ['test_form'=>false]);
        if(isset($up['url'])) wp_send_json_success(['url'=>$up['url']]);
        else wp_send_json_error();
    }

    /* BACKEND: Checkout Logic */
    public function ajax_checkout() {
        parse_str($_POST['data'], $form);
        WC()->cart->empty_cart();

        $custom_data = [
            'visa_full_info' => [
                'nationality' => $form['nationality'],
                'arrival' => $form['arrival_date'],
                'passport' => $form['passport_url'],
                'photo' => $form['photo_url'],
                'fullname' => $form['fullname'],
                'email' => $form['email'],
                'phone' => $form['phone']
            ]
        ];

        $added = WC()->cart->add_to_cart( $form['product_id'], 1, $form['variation_id'], [], $custom_data );

        if($added) {
            $c = WC()->customer;
            $c->set_billing_first_name($form['fullname']);
            $c->set_billing_email($form['email']);
            $c->set_billing_phone($form['phone']);
            $c->set_billing_country('VN'); 
            $c->set_billing_address_1('Visa Application Online');
            $c->set_billing_city('Hanoi');
            $c->set_billing_postcode('10000');
            $c->save();
            wp_send_json_success(['redirect' => wc_get_checkout_url()]);
        } else {
            wp_send_json_error(['message' => 'Cannot add to cart. Please try again.']);
        }
    }

    /* WOOCOMMERCE: Save Order Meta */
    public function save_order_meta($item, $key, $values, $order) {
        if(isset($values['visa_full_info'])) {
            $d = $values['visa_full_info'];
            $item->add_meta_data('Nationality', $d['nationality']);
            $item->add_meta_data('Arrival Date', $d['arrival']);
            $item->add_meta_data('Passport', $d['passport']);
            $item->add_meta_data('Photo', $d['photo']);
        }
    }

    /* WOOCOMMERCE: Clean Checkout */
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

new Visa_Five_Step_Wizard();