<?php
/**
 * Settings Main Template
 *
 * @package   OnePlace\Connect
 * @copyright 2019 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/connect
 */
?>
<div class="plc-admin">
    <div class="plc-settings-wrapper">
        <!-- Header START -->
        <div class="plc-settings-header">
            <div class="plc-settings-header-main">
                <div class="plc-settings-header-col header-col-first">
                    <div class="plc-settings-header-main-title">
                        WP PLC Connect <small>Version <?=(defined('WPPLC_CONNECT_VERSION')) ? WPPLC_CONNECT_VERSION : '(unknown)'?></small>
                    </div>
                </div>
                <div class="plc-settings-header-col header-col-second">
                    <img src="<?=plugins_url('assets/img/icon.png', WPPLC_CONNECT_PLUGIN_MAIN_FILE)?>" />
                </div>
                <div class="plc-settings-header-col header-col-third">
                    <a href="https://t.me/OnePlc" target="_blank" title="Telegram Support">
                        <?=__('Need help?','wp-plc-connect')?>
                    </a>
                </div>
            </div>
        </div>
        <!-- Header END -->
        <main class="plc-admin-main">
            <!-- Menu START -->
            <div class="plc-admin-menu-container">
                <nav class="plc-admin-menu">
                    <ul class="plc-admin-menu-list">
                        <li class="plc-admin-menu-list-item">
                            <a href="#/general">
                                <?=__('Settings','wp-plc-connect')?>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="plc-admin-alert-container"></div>
            </div>
            <!-- Menu END -->

            <!-- Content START -->
            <div class="plc-admin-page-container">
                <?php wp_nonce_field( 'oneplace-settings-update' ); ?>
                <?php
                # Include Settings Pages
                require_once __DIR__.'/partials/general.php';
                ?>
            </div>
            <!-- Content END -->
        </main>
    </div>
</div>