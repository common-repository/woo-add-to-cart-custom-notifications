<?php
/**
 * Woo Add To Cart Custom Notifications.
 *
 * @package   Woo_Add_To_Cart_Custom_Notifications
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

class Woo_Add_To_Cart_Custom_Notifications
{

    /**
     * Plugin version name
     *
     * @since   1.0.0
     *
     * @var     string
     */
    private static $VERSION_NAME = 'woo_add_to_cart_custom_notifications_version';

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    private static $VERSION = '1.0.0';

    /**
     * Unique identifier for your plugin.
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    private static $PLUGIN_SLUG = 'woo-add-to-cart-custom-notifications';

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

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));

        add_filter('wc_add_to_cart_message', array($this, 'add_to_cart_custom_message'), 10, 2);

    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug()
    {
        return self::$PLUGIN_SLUG;
    }

    /**
     * Return the plugin version.
     *
     * @since    1.0.0
     *
     * @return    Plugin version variable.
     */
    public function get_plugin_version()
    {
        return self::$VERSION;
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
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();

            } else {
                self::single_activate();
            }

        } else {
            self::single_activate();
        }

    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();

                }

                restore_current_blog();

            } else {
                self::single_deactivate();
            }

        } else {
            self::single_deactivate();
        }

    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     *
     * @param    int    $blog_id    ID of the new blog.
     */
    public function activate_new_site($blog_id)
    {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();

    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     *
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids()
    {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
            WHERE archived = '0' AND spam = '0'
            AND deleted = '0'";

        return $wpdb->get_col($sql);

    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate()
    {
        update_option(self::$VERSION_NAME, self::$VERSION);

        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        $domain = self::$PLUGIN_SLUG;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');

    }

    /**
     * Add custom add-to-cart message
     *
     * @since    1.0.0
     */
    public function add_to_cart_custom_message($message, $product_id)
    {
        global $woocommerce;

        // Check if the product has redirect url
        $custom_message = get_post_meta($product_id, '_watc_product_custom_notifications_message', true);

        $tag_product_title = '';
        // Handle grouped products
        if (is_array($product_id)) {
            foreach ($product_id as $id) {
                $tag_product_title .= get_the_title($id) . ' ';
            }
        } else {
            $tag_product_title = get_the_title($product_id);
        }
        // Special tags
        $message_tags = array(
            '{PRODUCT_NAME}' => $tag_product_title,
        );

        $message_tags_keys   = array_keys($message_tags);
        $message_tags_values = array_values($message_tags);

        // Redirect to the product-specific redirect url
        if (!empty($custom_message)) {
            // Apply custom message tags to message text
            $custom_message = str_replace($message_tags_keys, $message_tags_values, $custom_message);
            $message        = sprintf('<a href="%s" class="button wc-forwards">%s</a> %s', esc_url(wc_get_page_permalink('cart')), __('View Cart', 'woocommerce'), __($custom_message, 'woocommerce'));
            return $message;
        }
        // Otherwise - check if the global redirect url is defined
        $custom_message = get_option('watc_custom_notifications_message');
        // Redirect to the global redirect url
        if (!empty($custom_message)) {
            // Apply custom message tags to message text
            $custom_message = str_replace($message_tags_keys, $message_tags_values, $custom_message);
            $message        = sprintf('<a href="%s" class="button wc-forwards">%s</a> %s', esc_url(wc_get_page_permalink('cart')), __('View Cart', 'woocommerce'), __($custom_message, 'woocommerce'));
            return $message;
        }

        return $message;
    }

}
