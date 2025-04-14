<?php
/**
 * Plugin name: Comet Calendar
 * Description: Centrally manage event information and display it using Comet Components.
 * Author:              Double-E Design
 * Author URI:          https://www.doubleedesign.com.au
 * Version:             0.0.2
 * Requires at least:   6.7.0
 * Requires PHP:        8.2.23
 * Requires plugins:    comet-plugin, advanced-custom-fields-pro, block-supports-extended
 * Text Domain:         comet
 *
 * @package Comet
 */

const COMET_CALENDAR_VERSION = '0.0.2';
define('COMET_CALENDAR_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once __DIR__ . '/vendor/autoload.php';

use Doubleedesign\Comet\WordPress\Calendar\{
	Events,
	Fields,
	Admin
};

/**
 * Create activation and deactivation hooks and functions, so we can do things
 * when the plugin is activated, deactivated, or uninstalled.
 * These need to be in this plugin root file to work, so to run our plugin's functions from within its
 * classes, we simply call a function (from the plugin class) inside the function that needs to be here.
 * @return void
 */
function activate_comet_calendar(): void {
}
function deactivate_comet_calendar(): void {
}
function uninstall_comet_calendar(): void {
	// TODO Delete all options set and events created by this plugin
}
register_activation_hook(__FILE__, 'activate_comet_calendar');
register_deactivation_hook(__FILE__, 'deactivate_comet_calendar');
register_uninstall_hook(__FILE__, 'uninstall_comet_calendar');


// Load and run the rest of the plugin
new Fields();
new Events();
new Admin();
