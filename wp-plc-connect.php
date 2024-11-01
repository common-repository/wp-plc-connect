<?php
/**
 * Plugin main file.
 *
 * @package   OnePlace\Connect
 * @copyright 2020 OnePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 *
 * @wordpress-plugin
 * Plugin Name: WP PLC Connect
 * Plugin URI:  https://1plc.ch/wordpress-plugins/connect
 * Description: Connect your Wordpress installation to onePlace. Needed for other onePlace WP Plugins.
 * Version:     1.0.3
 * Author:      onePlace
 * Author URI:  https://1plc.ch
 * License:     GNU General Public License, version 2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: wp-plc-connect
 */

// Some basic security
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define global constants
define( 'WPPLC_CONNECT_VERSION', '1.0.3' );
define( 'WPPLC_CONNECT_PLUGIN_MAIN_FILE', __FILE__ );
define( 'WPPLC_CONNECT_PLUGIN_MAIN_DIR', __DIR__ );

/**
 * Handles plugin activation.
 *
 * Throws an error if the plugin is activated on an older version than PHP 5.4.
 *
 * @access private
 *
 * @param bool $network_wide Whether to activate network-wide.
 */
function wpplcconnect_activate_plugin( $network_wide ) {
    if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
        wp_die(
            esc_html__( 'WP PLC Events requires PHP version 5.4.', 'wp-plc-connect' ),
            esc_html__( 'Error Activating', 'wp-plc-shop' )
        );
    }
}

register_activation_hook( __FILE__, 'wpplcconnect_activate_plugin' );

if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/loader.php';
}