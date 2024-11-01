<?php

/**
 * Plugin loader.
 *
 * @package   OnePlace\Connect
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/connect
 */

namespace OnePlace\Connect;

/**
 * Main class for the plugin
 */
final class Plugin {
    /**
     * Main instance of the plugin.
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * Retrieves the main instance of the plugin.
     *
     * @return Plugin Plugin main instance.
     * @since 1.0.0
     */
    public static function instance() {
        return static::$instance;
    }

    /**
     * Registers the plugin with WordPress.
     *
     * @since 1.0.0
     */
    public function register() {
        # Enable Settings Page
        Modules\Settings::load();
    }

    /**
     * Loads the plugin main instance and initializes it.
     *
     * @param string $main_file Absolute path to the plugin main file.
     * @return bool True if the plugin main instance could be loaded, false otherwise.
     * @since 1.0.0
     */
    public static function load( $main_file ) {
        if ( null !== static::$instance ) {
            return false;
        }
        static::$instance = new static( $main_file );
        static::$instance->register();
        return true;
    }

    /**
     * Get Data from onePlace API Server
     *
     * @param $sUrl Url on API Server
     * @param array $aParams extra parameters to send with
     * @return bool|mixed false or json object
     * @since 1.0.0
     */
    public static function getDataFromAPI($sUrl,$aParams = [],$aBodyData = []) {
        # Get options
        $sHost = get_option('plcconnect_server_url');
        $sHostKey = get_option('plcconnect_server_key');
        $sHostToken = get_option('plcconnect_server_token');

        # if host is not set - its likely after setup
        if($sHost == '') {
            # todo: better error handling
            #echo 'oneplace not connected!';
            return false;
        } else {
            # Add Extra Params if set
            $sExtraParams = '';
            #$aRequestParams = ['authkey'=>$sHostKey,'authtoken'=>$sHostToken];
            if(count($aParams) > 0) {
                foreach(array_keys($aParams) as $sParamKey) {
                    $sExtraParams .= '&'.strtolower($sParamKey).'='.$aParams[$sParamKey];
                    #$aRequestParams[strtolower($sParamKey)] = $aParams[$sParamKey];
                }
            }
            if(count($aBodyData) > 0) {
                $aResponse = wp_remote_post($sHost . $sUrl . '?authkey=' . $sHostKey . '&authtoken=' . $sHostToken.$sExtraParams, [
                        'method' => 'POST',
                        'timeout' => 45,
                        'redirection' => 5,
                        'httpversion' => '1.0',
                        'blocking' => true,
                        'headers' => [],
                        'body' => $aBodyData,
                        'cookies' => [],
                    ]
                );
                //echo $aResponse['body'];
                if ( is_array( $aResponse ) && ! is_wp_error( $aResponse ) ) {
                    #$headers = $aResponse['headers']; // array of http header lines
                    $body    = $aResponse['body']; // use the content
                    $oJson = json_decode($body);

                    # Return json
                    return $oJson;
                } else {
                    # todo: better error handling
                    #echo 'invalid response from API server';
                    return $aResponse;
                }
            } else {
                # Get Data from API
                $aResponse = wp_remote_get($sHost . $sUrl . '?authkey=' . $sHostKey . '&authtoken=' . $sHostToken.$sExtraParams);
                //echo $aResponse['body'];
                if ( is_array( $aResponse ) && ! is_wp_error( $aResponse ) ) {
                    #$headers = $aResponse['headers']; // array of http header lines
                    $body    = $aResponse['body']; // use the content
                    $oJson = json_decode($body);

                    if(is_object($oJson)) {
                        # Return json
                        return $oJson;
                    } else {
                        return $aResponse;
                    }


                } else {
                    # todo: better error handling
                    #echo 'invalid response from API server';
                    return $aResponse;
                }
            }
        }

        return false;
    }

    /**
     * Get CDN Server Address
     *
     * @return mixed
     * @since 1.0.0
     */
    public static function getCDNServerAddress() {
        # Get Server URL (currently same as API Server, may change in future)
        $sHost = get_option('plcconnect_server_url');
        return $sHost;
    }
}