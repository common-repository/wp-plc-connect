<?php

/**
 * Plugin General Settings
 *
 * @package   OnePlace\Connect\Modules
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/connect
 */

namespace OnePlace\Connect\Modules;

use OnePlace\Connect\Plugin;

final class Settings {
    /**
     * Main instance of the module
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Register Plugin
     *
     * @since 1.0.0
     */
    public function register() {
        # add custom scripts for admin section
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );

        # Add submenu page for settings
        add_action("admin_menu", [ $this, 'addAdminMenu' ]);

        # Register Settings
        add_action( 'admin_init', [ $this, 'registerSettings' ] );

        # Add Plugin Languages
        add_action('plugins_loaded', [ $this, 'loadTextDomain' ] );

        # Register Update Settings AJAX Hook
        add_action('wp_ajax_save_plcsettings', [ $this, 'updateSettings' ] );
    }

    /**
     * load text domain (translations)
     *
     * @since 1.0.0
     */
    public function loadTextDomain() {
        load_plugin_textdomain( 'wp-plc-connect', false, dirname( plugin_basename(WPPLC_CONNECT_PLUGIN_MAIN_FILE) ) . '/language/' );
    }

    /**
     * Register Plugin Settings in Wordpress
     *
     * @since 1.0.0
     */
    public function registerSettings() {
        # Core Settings
        register_setting( 'wpplc_connect', 'plcconnect_server_url', false );
        register_setting( 'wpplc_connect', 'plcconnect_server_key', false );
        register_setting( 'wpplc_connect', 'plcconnect_server_token', false );
    }

    /**
     * Enqueue Style and Javascript for Admin Panel
     *
     * @param string hook
     * @since 1.0.0
     */
    public function enqueueScripts( $sHook ) {
        # add necessary css files
        wp_enqueue_style( 'plc-admin-style', plugins_url('assets/css/plc-admin-style.css', WPPLC_CONNECT_PLUGIN_MAIN_FILE));

        # add necessary js files
        wp_enqueue_script( 'plc-admin-controls', plugins_url('assets/js/plc-admin.js', WPPLC_CONNECT_PLUGIN_MAIN_FILE), [ 'jquery' ] );
        wp_localize_script('plc-admin-controls', 'plcAdminControls', [
            'pluginUrl' => plugins_url('',WPPLC_CONNECT_PLUGIN_MAIN_FILE),
        ]);
    }

    /**
     * Add Submenu Page to OnePlace Settings Menu
     *
     * @since 1.0.0
     */
    public function addAdminMenu() {
        $page_title = 'OnePlace Connect';
        $menu_title = 'OnePlace';
        $capability = 'manage_options';
        $menu_slug  = 'oneplace-connect';
        $function   = [$this,'renderSettingsPage'];
        $icon_url   = 'dashicons-media-code';
        $position   = 4;

        # Add Main Menu
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

        # Add Submenu (because there will be more from other oneplace plugins)
        add_submenu_page( $menu_slug, $page_title, $page_title, $capability, $menu_slug );
    }

    /**
     * Render Settings Page for Plugin
     *
     * @since 1.0.0
     */
    public function renderSettingsPage() {
        if(file_exists(__DIR__.'/../templates/settings.php')) {
            require_once __DIR__ . '/../templates/settings.php';
        } else {
            echo 'Could not find settings page template';
        }
    }

    /**
     * Loads the module main instance and initializes it.
     *
     * @return bool True if the plugin main instance could be loaded, false otherwise.
     * @since 1.0.0
     */
    public static function load() {
        if ( null !== static::$instance ) {
            return false;
        }
        static::$instance = new self();
        static::$instance->register();
        return true;
    }

    /**
     * Update onePlace Wordpress Settings
     *
     * Used by all WP PLC Plugins
     *
     * @since 1.0.2
     */
    public function updateSettings() {
        # only execute if started from our javascript
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bRunUpdate = true;

            # only run if everything is ok
            if($bRunUpdate) {
                # get settings and nonce
                $aSettings = (isset($_REQUEST['settings'])) ? (array) $_REQUEST['settings'] : [];
                # settings get sanitized before update_option see below
                $aSettings = array_map( 'esc_attr', $aSettings );
                $sWPNonce = wp_strip_all_tags($_REQUEST['nonce']);

                # only save settings if we have valid nonce
                if (wp_verify_nonce($sWPNonce, 'oneplace-settings-update')) {
                    # update all settings
                    foreach (array_keys($aSettings) as $sSetting) {
                        # another basic check if the setting is really ours
                        $bIsPlcSetting = stripos($sSetting, 'plc');
                        if ($bIsPlcSetting === false) {
                            # its not a field we sent
                        } else {
                            update_option($sSetting, str_replace(['\\'], [], sanitize_text_field($aSettings[$sSetting])));
                        }
                    }
                    $aResponse = ['state' => 'success', 'message' => 'Settings saved successfully'];
                } else {
                    $aResponse = ['state' => 'error', 'message' => 'invalid wordpress nonce'];
                }

                # Response is JSON
                header( "Content-Type: application/json" );
                echo json_encode($aResponse);
            }
        }

        //Don't forget to always exit in the ajax function.
        exit();
    }
}