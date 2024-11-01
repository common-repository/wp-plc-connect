<?php
/**
 * Settings General Partial
 *
 * @package   OnePlace\Connect
 * @copyright 2019 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/connect
 */
?>
<article class="plc-admin-page-general plc-admin-page">
    <h1><?=__('General Settings','wp-plc-connect')?></h1>
    <p><?=__('Here you find the core settings for your onePlace to Wordpress Connection','wp-plc-connect')?></p>

    <h3><?=__('onePlace Connection','wp-plc-connect')?></h3>

    <?php
    # Get Connection Data from options
    $sHost = get_option('plcconnect_server_url');
    $sAuthKey = get_option('plcconnect_server_key');
    $sAuthToken = get_option('plcconnect_server_token');

    if(substr($sHost,strlen($sHost)-1) == '/') {
        $sHost = substr($sHost,0,strlen($sHost)-1);
        update_option('plcconnect_server_url', $sHost);
    }

    # Lets try to connect if we have all necessary data
    if($sHost != '' && $sAuthKey != '' && $sAuthToken != '') {
        # Check API Response
        $aResponse = wp_remote_get($sHost.'/api?authkey='.$sAuthKey.'&authtoken='.$sAuthToken);
        if ( is_array( $aResponse ) && ! is_wp_error( $aResponse ) ) {
            #$headers = $aResponse['headers']; // array of http header lines
            $body    = $aResponse['body']; // use the content
            $oJson = json_decode($body);

            # Show Welcome Message if success
            if($oJson->state == 'success') {
                echo '<p style="color:green;">Response of '.$sHost.': '.$oJson->message.'</p>';
                echo '<a class="plc-admin-btn plc-admin-btn-primary" href="'.$sHost.'" target="_blank" title="Login to onePlace">';
                    echo __('Login to onePlace','wp-plc-connect');
                echo '</a>';
            } else {
                # Show error otherwise
                if(isset($oJson->message)) {
                    echo '<p style="color:green;">Error '.$oJson->message.'</p>';
                } else {
                    var_dump($oJson);
                    echo '<p style="color:green;">Unknown Error while connecting to API</p>';
                }
            }
        } else {
            echo 'Invalid Response from API Server';
        }
    } else { ?>
        <p style="color:red;"><?=__('OnePlace not connected!','wp-plc-connect')?></p>
    <?php } ?>
        <!-- Enter Connection Details -->
        <div style="float:left; width:100%; display: inline-block;">
            <h3><?=__('Connection Details','wp-plc-connect')?></h3>
            <!-- Server -->
            <div class="plc-admin-settings-field">
                <input type="text" class="plc-settings-value" name="plcconnect_server_url" value="<?=get_option('plcconnect_server_url')?>" />
                <span><?=__('onePlace URL','wp-plc-connect')?></span>
            </div>
            <!-- Server -->

            <!-- Authkey -->
            <div class="plc-admin-settings-field">
                <input type="text" class="plc-settings-value" name="plcconnect_server_key" value="<?=get_option('plcconnect_server_key')?>" />
                <span><?=__('onePlace Authkey','wp-plc-connect')?></span>
            </div>
            <!-- Authkey -->

            <!-- Authtoken-->
            <div class="plc-admin-settings-field">
                <input type="text" class="plc-settings-value" name="plcconnect_server_token" value="<?=get_option('plcconnect_server_token')?>" />
                <span><?=__('onePlace Authkey','wp-plc-connect')?></span>
            </div>
            <!-- Authtoken -->
        </div>
        <!-- Enter Connection Details -->


        <h3><?=__('Plugin Info','wp-plc-connect')?></h3>

        <?php
        /**
         * Check for other oneplace Plugins
         */
        if(is_plugin_active('wp-plc-events/wpplc-events.php')) { ?>
            <p style="color:green;">
                <?=__('Event Plugin loaded','wp-plc-connect')?> -
                <?=(defined('WPPLC_EVENTS_VERSION')) ? WPPLC_EVENTS_VERSION : '(unknwon)'?>
            </p>
        <?php } ?>
        <?php if(is_plugin_active('wp-plc-shop/wp-plc-shop.php')) { ?>
            <p style="color:green;">
                <?=__('Shop Plugin loaded','wp-plc-connect')?> -
                <?=(defined('WPPLC_SHOP_VERSION')) ? WPPLC_SHOP_VERSION : '(unknown)'?>
            </p>
        <?php } ?>

    <!-- Save Button -->
    <hr/>
    <button class="plc-admin-settings-save plc-admin-btn plc-admin-btn-primary" plc-admin-page="page-general">
        <?=__('Save General Settings','wp-plc-connect')?>
    </button>
    <!-- Save Button -->
</article>