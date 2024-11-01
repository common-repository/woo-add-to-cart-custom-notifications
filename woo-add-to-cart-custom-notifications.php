<?php
/**
 * @package   Woo_Add_To_Cart_Custom_Notifications
 * @author    KungWoo
 * @license   GPL-2.0+
 * @link      http://kungwoo.com
 * @copyright 2016 KungWoo
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Add To Cart Custom Notifications
 * Plugin URI:        https://kungwoo.com/plugins/woo-add-to-cart-custom-notifications
 * Description:       Set custom add-to-cart notifications.
 * Version:           1.0.0
 * Author:            KungWoo
 * Author URI:        http://kungwoo.com
 * Text Domain:       woo-add-to-cart-custom-notifications
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

/**
 *-----------------------------------------
 * Do not delete this line
 * Added for security reasons: http://codex.wordpress.org/Theme_Development#Template_Files
 *-----------------------------------------
 */
defined('ABSPATH') or die("Direct access to the script does not allowed");
/*-----------------------------------------*/

/*----------------------------------------------------------------------------*
 * * * ATTENTION! * * *
 * FOR DEVELOPMENT ONLY
 * SHOULD BE DISABLED ON PRODUCTION
 *----------------------------------------------------------------------------*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
/*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*
 * Plugin Settings
 *----------------------------------------------------------------------------*/

/* ----- Plugin Module: Woo Settings ----- */
require_once plugin_dir_path(__FILE__) . 'includes/class-woo-add-to-cart-custom-notifications-woo-settings.php';

add_action('plugins_loaded', array('Woo_Add_To_Cart_Custom_Notifications_Woo_Settings', 'get_instance'));
/* ----- Module End: Woo Settings ----- */

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once plugin_dir_path(__FILE__) . 'includes/class-woo-add-to-cart-custom-notifications.php';

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array('Woo_Add_To_Cart_Custom_Notifications', 'activate'));
register_deactivation_hook(__FILE__, array('Woo_Add_To_Cart_Custom_Notifications', 'deactivate'));

add_action('plugins_loaded', array('Woo_Add_To_Cart_Custom_Notifications', 'get_instance'));

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {

    require_once plugin_dir_path(__FILE__) . 'includes/admin/class-woo-add-to-cart-custom-notifications-admin.php';
    add_action('plugins_loaded', array('Woo_Add_To_Cart_Custom_Notifications_Admin', 'get_instance'));

}
