<?php
/**
 * Plugin name: Comet Components
 * Description: Double-E Design's foundational components and customisations for the WordPress block editor.
 *
 * Author:              Double-E Design
 * Author URI:          https://www.doubleedesign.com.au
 * Version:             0.0.1
 * Requires at least:   6.7.0
 * Requires PHP:        8.3.11
 * Requires plugins:	advanced-custom-fields-pro
 * Text Domain:         comet
 *
 * @package Comet
 */

const COMET_VERSION = '0.0.1';
require_once __DIR__ . '/vendor/autoload.php';

use Doubleedesign\Comet\WordPress\{EmbeddedPlugins, BlockRegistry, BlockEditorConfig, BlockEditorAdminAssets};

new EmbeddedPlugins();
new BlockRegistry();
new BlockEditorConfig();
new BlockEditorAdminAssets();
