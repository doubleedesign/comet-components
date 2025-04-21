<?php
namespace Doubleedesign\CometCanvas;

class BlockEditorConfig {

	function __construct() {
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_scripts'], 10, 2);
		// There should be a filter here for page template block restrictions, but it doesn't work from the theme so it's in the Comet Plugin
	}

	function enqueue_scripts(): void {
		wp_enqueue_script('comet-canvas-block-editor', get_template_directory_uri() . '/src/block-editor-config.js', ['wp-blocks', 'wp-element', 'wp-components'], false, true);
	}
}
