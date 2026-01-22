<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;


/**
 * Onepay Payment Gateway Blocks integration
 *
 * @since 1.0.0
 */
final class WC_Onepay_Blocks extends AbstractPaymentMethodType
{

    /**
     * The gateway instance.
     *
     * @var WC_Gateway_onepay
     */
    private $gatewayOP;

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name = 'onepay';

    /**
     * Initializes the payment method type.
     */
    public function initialize()
    {
        $this->settings = get_option('woocommerce_onepay_settings', []);
        // $gateways       = WC()->payment_gateways->payment_gateways();
        $this->gatewayOP  = new WC_Gateway_onepay;
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active()
    {
        return $this->gatewayOP->is_available();
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles()
    {

        $script_path_op = plugins_url('blocks/block.js', __DIR__);
        $dependancies_op = [
            'react',
            'wp-blocks',
            'wp-element',
            'wp-components',
            'wc-blocks-registry',
            'wc-settings',
            'wp-html-entities',
        ];
        wp_register_script(
            'wc-onepay-blocks-integration',
            $script_path_op,
            $dependancies_op,
            '1.0.0',
            true
        );

        return ['wc-onepay-blocks-integration'];
    }


    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data()
    {
        return [
            'title'         => $this->gatewayOP->title,
            'description'   => $this->gatewayOP->description,
            'icon'         => $this->gatewayOP->icon,
            'supports'      => array_filter($this->gatewayOP->supports, [$this->gatewayOP, 'supports'])
        ];
    }
}
