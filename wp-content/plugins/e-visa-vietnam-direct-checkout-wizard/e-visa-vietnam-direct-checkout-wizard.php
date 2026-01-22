<?php
/*
Plugin Name: E-Visa Vietnam Direct Checkout Wizard
Description: Multi-step E-Visa Vietnam Booking Form with Direct Checkout and Auto-Clean Session.
Version: 1.0
Author: DuyViet
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Visa_Direct_Checkout {

    public function __construct() {
        // Assets & Shortcode
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_shortcode( 'visa_wizard_form', array( $this, 'render_wizard' ) );

        // AJAX: Get Price
        add_action( 'wp_ajax_get_visa_price', array( $this, 'ajax_get_price' ) );
        add_action( 'wp_ajax_nopriv_get_visa_price', array( $this, 'ajax_get_price' ) );

        // AJAX: File Upload
        add_action( 'wp_ajax_visa_upload', array( $this, 'ajax_upload' ) );
        add_action( 'wp_ajax_nopriv_visa_upload', array( $this, 'ajax_upload' ) );

        // AJAX: Direct Checkout (Clear Cart + Add New + Redirect)
        add_action( 'wp_ajax_visa_direct_checkout', array( $this, 'ajax_direct_checkout' ) );
        add_action( 'wp_ajax_nopriv_visa_direct_checkout', array( $this, 'ajax_direct_checkout' ) );

        // WooCommerce Hooks (Data Handling)
        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'store_data' ), 10, 2 );
        add_filter( 'woocommerce_get_item_data', array( $this, 'display_data' ), 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_order_data' ), 10, 4 );
        add_filter( 'woocommerce_checkout_fields', array( $this, 'simplify_checkout' ) );
    }

    /* ================= FRONTEND UI (WIZARD) ================= */

    public function enqueue_assets() {
        wp_enqueue_script( 'jquery' );
        wp_add_inline_style( 'wp-block-library', '
            /* Wizard Container */
            .visa-wizard { max-width: 900px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; font-family: sans-serif; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
            
            /* Progress Bar */
            .wizard-progress { display: flex; background: #f8f9fa; border-bottom: 1px solid #eee; }
            .progress-step { flex: 1; padding: 15px; text-align: center; font-size: 14px; color: #aaa; font-weight: 600; border-bottom: 3px solid transparent; }
            .progress-step.active { color: #007cba; border-bottom-color: #007cba; }
            .progress-step.completed { color: #28a745; border-bottom-color: #28a745; }

            /* Step Content */
            .step-content { padding: 30px; display: none; animation: fadeIn 0.4s; }
            .step-content.active { display: block; }
            @keyframes fadeIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }

            /* Form Fields */
            .form-group { margin-bottom: 20px; }
            .form-label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
            .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
            .form-desc { font-size: 0.85em; color: #666; margin-top: 5px; font-style: italic; }
            
            /* Buttons */
            .wizard-actions { padding: 20px 30px; background: #fafafa; border-top: 1px solid #eee; display: flex; justify-content: space-between; }
            .btn-visa { padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: 600; }
            .btn-next { background: #007cba; color: white; }
            .btn-next:hover { background: #006ba1; }
            .btn-back { background: #e2e6ea; color: #333; }
            .btn-back:hover { background: #dbe0e5; }
            .btn-finish { background: #28a745; color: white; }
            .btn-finish:hover { background: #218838; }

            /* Pricing & Alerts */
            .price-summary { background: #e8f0fe; border: 1px solid #b8daff; padding: 15px; border-radius: 4px; margin-top: 10px; color: #004085; display:none;}
            .alert-warning { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 10px; border-radius: 4px; font-size: 0.9em; margin-top: 10px; }
            .upload-msg { font-size: 0.9em; margin-top: 5px; font-weight: bold; }
            .error-msg { color: red; margin-top: 10px; display: none; }
        ' );
    }

    public function render_wizard( $atts ) {
        $atts = shortcode_atts( ['product_id' => 0], $atts );
        $product_id = intval($atts['product_id']);
        $product = wc_get_product( $product_id );

        if ( ! $product || ! $product->is_type( 'variable' ) ) return 'Invalid Product ID';
        $attributes = $product->get_variation_attributes();
        
        // Setup Lists
        $nationalities = ['Vietnam', 'United States', 'United Kingdom', 'Australia', 'Canada', 'France', 'Germany', 'Japan', 'South Korea', 'India', 'China'];
        $phone_codes = ['+84 (Vietnam)', '+1 (USA)', '+44 (UK)', '+61 (AUS)', '+81 (JPN)', '+82 (KOR)'];

        ob_start();
        ?>
        <div class="visa-wizard" id="visa_wizard">
            <div class="wizard-progress">
                <div class="progress-step active" data-step="1">1. Select Service</div>
                <div class="progress-step" data-step="2">2. Applicant Info</div>
                <div class="progress-step" data-step="3">3. Review & Pay</div>
            </div>

            <form id="visa_form_data">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="variation_id" id="variation_id">

                <div class="step-content active" id="step_1">
                    <div class="form-group">
                        <label class="form-label">Nationality</label>
                        <select name="nationality" class="form-control" required>
                            <option value="">Select Nationality</option>
                            <?php foreach($nationalities as $n) echo "<option value='$n'>$n</option>"; ?>
                        </select>
                        <div class="form-desc">A drop-down list of nationalities that we can accept bookings. This has no impact on pricing.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Visa Type</label>
                        <select name="visa_type" class="form-control pricing-trigger" required>
                            <option value="">Select Visa Type</option>
                            <?php foreach($attributes['visa-type'] as $term) echo "<option value='$term'>$term</option>"; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Processing Time</label>
                        <select name="processing_time" class="form-control pricing-trigger" required>
                            <option value="">Select Time</option>
                            <?php foreach($attributes['processing-time'] as $term) echo "<option value='$term'>$term</option>"; ?>
                        </select>
                        
                        <div id="price_box" class="price-summary">
                            Total: <strong id="price_val" style="font-size:1.2em">--</strong>
                            <br><small>Price is inclusive of government visa processing fee, service fee, and VAT.</small>
                        </div>
                        
                        <div class="alert-warning">
                            Note: Processing time counts from the time the application is confirmed, not submitted, during working hours (8:30AM - 4:30PM Mon-Fri).
                        </div>
                    </div>
                </div>

                <div class="step-content" id="step_2">
                    <div class="form-group">
                        <label class="form-label">Date of Arrival</label>
                        <input type="date" name="arrival_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Passport</label>
                        <input type="file" id="file_passport" accept="image/*,.pdf" class="form-control">
                        <input type="hidden" name="passport_url" id="passport_url" required>
                        <div class="form-desc">Upload passport photo.</div>
                        <div id="stat_passport" class="upload-msg"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Portrait Photo</label>
                        <input type="file" id="file_photo" accept="image/*" class="form-control">
                        <input type="hidden" name="photo_url" id="photo_url" required>
                        <div class="form-desc">Upload portrait photo.</div>
                        <div id="stat_photo" class="upload-msg"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Enter full name" required>
                    </div>
                </div>

                <div class="step-content" id="step_3">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <div style="display:flex; gap:10px;">
                            <select name="phone_code" style="width:35%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                                <?php foreach($phone_codes as $c) echo "<option value='$c'>$c</option>"; ?>
                            </select>
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
                        </div>
                    </div>

                    <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; height: 80px; overflow-y: scroll; font-size: 0.8em; margin-bottom: 15px;">
                        <strong>TERMS OF SERVICE</strong><br>
                        1. By applying, you agree to... <br>
                        2. Refund Policy: Fees are non-refundable... <br>
                        (Full terms text here...)
                    </div>

                    <label style="font-size: 0.9em; cursor: pointer;">
                        <input type="checkbox" name="agree" required>
                        By submitting payment, I acknowledge that I have read and accept the Terms of Service, Privacy Policy, and Refund Policy.
                    </label>
                    <div id="final_error" class="error-msg"></div>
                </div>
            </form>

            <div class="wizard-actions">
                <button type="button" class="btn-visa btn-back" id="btn_back" style="display:none;">&laquo; Back</button>
                <button type="button" class="btn-visa btn-next" id="btn_next">Next Step &raquo;</button>
                <button type="button" class="btn-visa btn-finish" id="btn_finish" style="display:none;">PROCEED TO PAYMENT</button>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($){
            var currentStep = 1;
            var totalSteps = 3;

            // 1. NAVIGATION LOGIC
            function showStep(step) {
                $('.step-content').removeClass('active');
                $('#step_' + step).addClass('active');
                
                // Update Progress Bar
                $('.progress-step').removeClass('active completed');
                for(var i=1; i<=totalSteps; i++) {
                    if(i < step) $('.progress-step[data-step="'+i+'"]').addClass('completed');
                    if(i === step) $('.progress-step[data-step="'+i+'"]').addClass('active');
                }

                // Buttons Visibility
                if(step === 1) $('#btn_back').hide(); else $('#btn_back').show();
                if(step === totalSteps) {
                    $('#btn_next').hide();
                    $('#btn_finish').show();
                } else {
                    $('#btn_next').show();
                    $('#btn_finish').hide();
                }
            }

            $('#btn_next').click(function(){
                // Validate Step 1
                if(currentStep === 1) {
                    if(!$('#variation_id').val()) { alert('Please select Visa Type & Time'); return; }
                    if(!$('select[name="nationality"]').val()) { alert('Please select Nationality'); return; }
                }
                // Validate Step 2
                if(currentStep === 2) {
                    if(!$('#passport_url').val() || !$('#photo_url').val()) { alert('Please upload both Passport and Photo'); return; }
                    if(!$('input[name="arrival_date"]').val()) { alert('Please select Arrival Date'); return; }
                    if(!$('input[name="fullname"]').val()) { alert('Please enter Full Name'); return; }
                }

                currentStep++;
                showStep(currentStep);
            });

            $('#btn_back').click(function(){
                currentStep--;
                showStep(currentStep);
            });

            // 2. PRICING LOGIC
            $('.pricing-trigger').change(function(){
                var type = $('select[name="visa_type"]').val();
                var time = $('select[name="processing_time"]').val();
                if(type && time) {
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                        action: 'get_visa_price',
                        product_id: $('input[name="product_id"]').val(),
                        type: type,
                        time: time
                    }, function(res){
                        if(res.success) {
                            $('#price_val').html(res.data.price_html);
                            $('#variation_id').val(res.data.variation_id);
                            $('#price_box').fadeIn();
                        } else {
                            $('#price_box').hide();
                        }
                    });
                }
            });

            // 3. FILE UPLOAD
            function initUpload(input, hidden, stat) {
                $(input).change(function(){
                    var fd = new FormData();
                    fd.append('file', this.files[0]);
                    fd.append('action', 'visa_upload');
                    $(stat).text('Uploading...').css('color','blue');
                    
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST', contentType: false, processData: false, data: fd,
                        success: function(res){
                            if(res.success){
                                $(hidden).val(res.data.url);
                                $(stat).text('Uploaded successfully').css('color','green');
                            } else {
                                $(stat).text(res.data).css('color','red');
                            }
                        }
                    });
                });
            }
            initUpload('#file_passport', '#passport_url', '#stat_passport');
            initUpload('#file_photo', '#photo_url', '#stat_photo');

            // 4. FINAL SUBMIT (DIRECT CHECKOUT)
            $('#btn_finish').click(function(){
                var form = $('#visa_form_data');
                // HTML5 Validate
                if(!document.getElementById('visa_form_data').checkValidity()){
                    document.getElementById('visa_form_data').reportValidity(); return;
                }

                $(this).text('PROCESSING...').prop('disabled', true);
                
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                    action: 'visa_direct_checkout',
                    data: form.serialize()
                }, function(res){
                    if(res.success) {
                        window.location.href = res.data.redirect;
                    } else {
                        $('#final_error').text(res.data.message).show();
                        $('#btn_finish').text('PROCEED TO PAYMENT').prop('disabled', false);
                    }
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    /* ================= BACKEND LOGIC ================= */

    // Get Matrix Price
    public function ajax_get_price() {
        $pid = intval($_POST['product_id']);
        $type = $_POST['type'];
        $time = $_POST['time'];
        
        $data_store = WC_Data_Store::load( 'product' );
        $vid = $data_store->find_matching_product_variation( new WC_Product($pid), [
            'attribute_pa_visa-type' => $type, 
            'attribute_pa_processing-time' => $time
        ]);

        if($vid) {
            $v = wc_get_product($vid);
            wp_send_json_success(['variation_id'=>$vid, 'price_html'=>$v->get_price_html()]);
        }
        wp_send_json_error();
    }

    // Upload Handler
    public function ajax_upload() {
        if(!function_exists('wp_handle_upload')) require_once(ABSPATH.'wp-admin/includes/file.php');
        $up = wp_handle_upload($_FILES['file'], ['test_form'=>false]);
        if(isset($up['url'])) wp_send_json_success(['url'=>$up['url']]);
        else wp_send_json_error($up['error']);
    }

    // DIRECT CHECKOUT HANDLER (CORE LOGIC)
    public function ajax_direct_checkout() {
        parse_str($_POST['data'], $form);

        // 1. FORCE EMPTY CART (Rule: No old products)
        WC()->cart->empty_cart();

        // 2. Add New Product
        $custom_data = [
            'visa_info' => [
                'nationality' => $form['nationality'],
                'arrival' => $form['arrival_date'],
                'passport' => $form['passport_url'],
                'photo' => $form['photo_url'],
                'fullname' => $form['fullname'],
                'email' => $form['email'],
                'phone' => $form['phone_code'].' '.$form['phone']
            ]
        ];
        
        $added = WC()->cart->add_to_cart( $form['product_id'], 1, $form['variation_id'], [], $custom_data );

        if($added) {
            // 3. Set Customer Data for Checkout Page
            $customer = WC()->customer;
            $customer->set_billing_first_name( $form['fullname'] );
            $customer->set_billing_email( $form['email'] );
            $customer->set_billing_phone( $form['phone_code'].' '.$form['phone'] );
            $customer->set_billing_country('VN'); // Default to avoid validation error
            $customer->set_billing_address_1('Online Visa Application'); // Dummy data
            $customer->set_billing_city('Hanoi');
            $customer->save();

            // 4. Return Checkout URL
            wp_send_json_success(['redirect' => wc_get_checkout_url()]);
        } else {
            wp_send_json_error(['message' => 'System error. Could not create booking.']);
        }
    }

    /* ================= WOOCOMMERCE HOOKS ================= */

    public function store_data($cart_data) {
        if(isset($_POST['visa_info'])) $cart_data['visa_info'] = $_POST['visa_info']; // Backup hook
        return $cart_data;
    }

    public function display_data($item_data, $cart_item) {
        if(isset($cart_item['visa_info'])) {
            $d = $cart_item['visa_info'];
            $item_data[] = ['key'=>'Nationality', 'value'=>$d['nationality']];
            $item_data[] = ['key'=>'Arrival', 'value'=>$d['arrival']];
            $item_data[] = ['key'=>'Applicant', 'value'=>$d['fullname']];
        }
        return $item_data;
    }

    public function save_order_data($item, $cart_item_key, $values, $order) {
        if(isset($values['visa_info'])) {
            $d = $values['visa_info'];
            $item->add_meta_data('Nationality', $d['nationality']);
            $item->add_meta_data('Arrival Date', $d['arrival']);
            $item->add_meta_data('Full Name', $d['fullname']);
            $item->add_meta_data('Passport Link', $d['passport']);
            $item->add_meta_data('Photo Link', $d['photo']);
            $item->add_meta_data('Phone', $d['phone']);
        }
    }

    public function simplify_checkout($fields) {
        // Hide almost everything, we already collected info
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);
        unset($fields['billing']['billing_country']); // Hide country select
        unset($fields['shipping']);
        // Name, Email, Phone will be pre-filled but visible for confirmation
        return $fields;
    }
}

new Visa_Direct_Checkout();