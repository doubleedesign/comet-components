<?php

namespace Doubleedesign\Comet\WordPress;

class BlockEditorConfig extends JavaScriptImplementation {
	
	function __construct() {
		parent::__construct();
		
		add_filter('should_load_remote_block_patterns', '__return_false');
		remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
		remove_action('enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory');
		
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_companion_javascript'], 100);
		add_filter('block_categories_all', [$this, 'customise_block_categories']);
		add_action('init', [$this, 'allowed_block_patterns'], 10, 2);
		add_filter('use_block_editor_for_post_type', [$this, 'selective_gutenberg'], 10, 2);
		add_action('after_setup_theme', [$this, 'disable_block_template_editor']);
		add_filter('block_editor_settings_all', [$this, 'disable_block_code_editor'], 10, 2);
	}
	
	/**
	 * Register custom block categories and customise some existing ones
	 * @param $categories
	 *
	 * @return array
	 */
	function customise_block_categories($categories): array {
		// Add categories
		$categories[] = array('slug' => 'content', 'title' => 'Content');
		
		$updated = array_map(function ($category) {
			if ($category['slug'] === 'design') {
				$category['title'] = 'Layout';
			}
			
			return $category;
		}, $categories);
		
		$preferred_order = array('text', 'design', 'media', 'content');
		usort($updated, function ($a, $b) use ($preferred_order) {
			return array_search($a['slug'], $preferred_order) <=> array_search($b['slug'], $preferred_order);
		});
		
		return $updated;
	}
	
	/**
	 * Disable some core Block Patterns for simplicity
	 * and register custom patterns
	 * Note: Also ensure loading of remote patterns is disabled using add_filter('should_load_remote_block_patterns', '__return_false');
	 *
	 * @return void
	 */
	function allowed_block_patterns(): void {
		unregister_block_pattern('core/query-offset-posts');
		unregister_block_pattern('core/query-large-title-posts');
		unregister_block_pattern('core/query-grid-posts');
		unregister_block_pattern('core/query-standard-posts');
		unregister_block_pattern('core/query-medium-posts');
		unregister_block_pattern('core/query-small-posts');
		
		// TODO: Register custom block patterns
	}
	
	/**
	 * Only use the block editor for certain content types
	 * @param $current_status
	 * @param $post_type
	 *
	 * @return bool
	 */
	function selective_gutenberg($current_status, $post_type): bool {
		if ($post_type === 'page' || $post_type === 'shared_content') {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Disable block template editor option
	 * @return void
	 */
	function disable_block_template_editor(): void {
		remove_theme_support('block-templates');
	}
	
	/**
	 * Disable access to the block code editor
	 */
	function disable_block_code_editor($settings, $context) {
		$settings['codeEditingEnabled'] = false;
		
		return $settings;
	}
}
