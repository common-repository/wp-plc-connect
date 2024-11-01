<?php

/**
 * Plugin loader.
 *
 * @package   OnePlace\Connect
 * @copyright 2019 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch/wordpress-plugins/connect
 */

namespace OnePlace\Connect;

/**
 * Load composer autoload files
 *
 */
//require __DIR__ . '/vendor/autoload.php';

// Load Plugin
require_once __DIR__.'/Plugin.php';

// Load Modules
require_once __DIR__.'/Modules/Settings.php';

// Init Plugin
Plugin::load(WPPLC_CONNECT_PLUGIN_MAIN_FILE);

