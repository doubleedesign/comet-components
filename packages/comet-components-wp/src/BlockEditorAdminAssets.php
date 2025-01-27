<?php
namespace Doubleedesign\Comet\WordPress;

/**
 * This class handles loading of CSS and JS assets in the block editor
 */
class BlockEditorAdminAssets {

	function __construct() {
		if (!function_exists('register_block_type')) {
			// Block editor is not available.
			return;
		}
		add_action('enqueue_block_assets', [$this, 'enqueue_global_css'], 1);
		add_action('enqueue_block_assets', [$this, 'enqueue_block_css'], 1);
		add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
	}

	/**
	 * Global stylesheet for basics like typography, colors, etc.
	 * @return void
	 */
	function enqueue_global_css(): void {
		$currentDir = plugin_dir_url(__FILE__);
		$pluginDir = dirname($currentDir, 1);
		$global_css_path = $pluginDir . '/vendor/doubleedesign/comet-components-core/src/components/global.css';
		wp_enqueue_style('comet-global-styles', $global_css_path, array(), COMET_VERSION);
	}

	/**
	 * Combined stylesheet for all blocks
	 * @return void
	 */
	function enqueue_block_css(): void {
		$currentDir = plugin_dir_url(__FILE__);
		$pluginDir = dirname($currentDir, 1);
		$block_css_path = $pluginDir . '/src/blocks.css';
		wp_enqueue_style('comet-block-styles', $block_css_path, array(), COMET_VERSION);
	}

	/**
	 * Scripts to hackily remove/hide menu items (e.g., the disabled code editor button) for simplicity,
	 * open list view by default, and other editor UX things like that
	 *
	 * @return void
	 */
	function admin_scripts(): void {
		$currentDir = plugin_dir_url(__FILE__);
		$pluginDir = dirname($currentDir, 1);

		//wp_enqueue_script('comet-block-editor-hacks', './block-editor-hacks.js', array('wp-edit-post', 'wp-data', 'wp-dom-ready'), COMET_VERSION, true);
		wp_enqueue_style('comet-block-editor-hacks', "$pluginDir/src/block-editor-hacks.css", array(), COMET_VERSION);
	}
}
