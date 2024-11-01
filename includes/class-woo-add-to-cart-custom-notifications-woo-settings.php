<?php
/**
 * Woo Add To Cart Custom Notifications.
 *
 * @package   Woo_Add_To_Cart_Custom_Notifications_Woo_Settings
 * @author    KungWoo
 * @license   GPL-2.0+
 * @link      http://kungwoo.com
 * @copyright 2016 KungWoo
 */

/**
 *-----------------------------------------
 * Do not delete this line
 * Added for security reasons: http://codex.wordpress.org/Theme_Development#Template_Files
 *-----------------------------------------
 */
defined('ABSPATH') or die("Direct access to the script does not allowed");
/*-----------------------------------------*/

/**
 * WooCommerce Settings
 */
class Woo_Add_To_Cart_Custom_Notifications_Woo_Settings
{

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct()
    {

        // Register WooCommerce Settings Section
        add_filter('woocommerce_get_sections_products', array($this, 'register_sections'));

        // Register WooCommerce Settings
        add_filter('woocommerce_get_settings_products', array($this, 'register_settings'), 10, 2);

        // Register WooCommerce Product Options
        add_action('woocommerce_product_options_general_product_data', array($this, 'register_product_options'));
        // Save WooCommerce Product Options
        add_action('woocommerce_process_product_meta', array($this, 'save_product_options'));

    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Add section to WooCommerce products settings page
     *
     * @since     1.0.0
     */
    public function register_sections($sections)
    {
        $sections['watc_custom_notifications'] = __('Custom Notifications', 'woo-add-to-cart-custom-notifications');

        return $sections;
    }

    /**
     * Add WooCommerce settings
     *
     * @since     1.0.0
     */
    public function register_settings($settings, $current_section)
    {
        /**
         * Check the current section is what we want
         **/
        if ($current_section == 'watc_custom_notifications') {
            $new_settings = array();
            // Section Title
            $new_settings[] = array(
                'id'   => 'watc_custom_notifications_title',
                'name' => __('Custom Redirect', 'woo-add-to-cart-custom-redirect'),
                'type' => 'title',
                'desc' =>
                __('Set custom add-to-cart message.') . '<br>' .
                __('You can specify custom message for each product separately.') . '<br>' .
                '<b>' . __('Available special tags', 'woo-add-to-cart-custom-notifications') . ':' . '</b>' . '<br>' .
                __('{PRODUCT_NAME} - Product name', 'woo-add-to-cart-custom-notifications') . '<br>',
            );
            $new_settings[] = array(
                'id'          => 'watc_custom_notifications_message',
                'name'        => __('Message on Add to Cart', 'woo-add-to-cart-custom-notifications'),
                'type'        => 'text',
                'class'       => 'large-text',
                'placeholder' => '"{PRODUCT_NAME}" has been added to your cart.',
                'desc'        => __('Enter custom add-to-cart message. HTML-tags are supported.', 'woo-add-to-cart-custom-redirect'),
                'desc_tip'    => true,
            );

            $new_settings[] = array('type' => 'sectionend', 'id' => 'watc_custom_notifications');

            return $new_settings;

        } else {

            return $settings;
        }
    }

    /**
     * Add product settings
     *
     * @since     1.0.0
     */
    public function register_product_options()
    {
        global $post_id, $woocommerce, $post;

        echo '<div class="options_group">';

        woocommerce_wp_text_input(

            array(
                'id'          => '_watc_product_custom_notifications_message',
                'label'       => __('Message on Add to Cart', 'woo-add-to-cart-custom-notifications'),
                'placeholder' => '"{PRODUCT_NAME}" has been added to your cart.',
                'desc_tip'    => 'true',
                'description' => __('Enter custom add-to-cart message. HTML-tags are supported.', 'woo-add-to-cart-custom-notifications'),
                'value'       => get_post_meta($post_id, '_watc_product_custom_notifications_message', true),
            )

        );

        echo '</div>';
    }

    /**
     * Save product settings
     *
     * @since     1.0.0
     */
    public function save_product_options($post_id)
    {

        $custom_message = isset($_POST['_watc_product_custom_notifications_message']) ? $_POST['_watc_product_custom_notifications_message'] : '';

        update_post_meta($post_id, '_watc_product_custom_notifications_message', $custom_message);

    }
}
