<?php
namespace Doubleedesign\Comet\WordPress;

class BlockEditorConfig {

	function __construct() {
		add_filter('should_load_remote_block_patterns', '__return_false');
		remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
		remove_action('enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory');

		add_action('init', [$this, 'load_plugin_theme_json']);

		add_filter('block_categories_all', [$this, 'customise_block_categories'], 10, 1);
		add_filter('register_block_type_args', [$this, 'assign_blocks_to_categories'], 11, 2);

		add_action('init', [$this, 'allowed_block_patterns'], 10, 2);
		add_filter('block_editor_settings_all', [$this, 'block_inspector_single_panel'], 10, 2);
		add_filter('use_block_editor_for_post_type', [$this, 'selective_gutenberg'], 10, 2);
		add_action('after_setup_theme', [$this, 'disable_block_template_editor']);
		add_filter('block_editor_settings_all', [$this, 'disable_block_code_editor'], 10, 2);
	}


	/**
	 * Load theme.json file from this plugin to set defaults of what's supported in the editor
	 * @return void
	 */
	function load_plugin_theme_json(): void {
		delete_option('wp_theme_json_data'); // clear cache

		$plugin_theme_json_path = plugin_dir_path(__FILE__) . 'theme.json';
		$plugin_theme_json_data = json_decode(file_get_contents($plugin_theme_json_path), true);

		add_filter('wp_theme_json_data_default', function ($theme_json) use ($plugin_theme_json_data) {
			if (is_array($plugin_theme_json_data)) {
				$new_data = $theme_json->get_data();
				$new_data = array_replace_recursive($new_data, $plugin_theme_json_data);
				return new WP_Theme_JSON_Data($new_data);
			}
			return $theme_json;
		});
	}


	/**
	 * Register custom block categories and customise some existing ones
	 * @param $categories
	 * @return array
	 */
	function customise_block_categories($categories): array {
		$block_support = json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'block-support.json'), true);
		$custom_categories = $block_support['categories'];
		$new_categories = [];

		foreach ($custom_categories as $cat) {
			$new_categories[] = [
				'slug'  => $cat['slug'],
				'title' => $cat['name'],
				'icon'  => $cat['icon']
			];
		}

		$preferred_order = array('design', 'text', 'media', 'content', 'embeds');
		usort($new_categories, function ($a, $b) use ($preferred_order) {
			return array_search($a['slug'], $preferred_order) <=> array_search($b['slug'], $preferred_order);
		});

		return $new_categories;
	}

	/**
	 * Modify block registration to set correct categories
	 * @param array $settings
	 * @param string $name
	 * @return array
	 */
	function assign_blocks_to_categories($settings, $name): array {
		static $block_category_map = null;

		if ($block_category_map === null) {
			$block_support = json_decode(file_get_contents(plugin_dir_path(__FILE__) . 'block-support.json'), true);
			$block_category_map = [];

			foreach ($block_support['categories'] as $category) {
				foreach ($category['blocks'] as $block_name) {
					$block_category_map[$block_name] = $category['slug'];
				}
			}
		}

		if (isset($block_category_map[$name])) {
			$settings['category'] = $block_category_map[$name];
		}

		return $settings;
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
	 * Display all block settings in one panel in the block inspector sidebar
	 * @param $settings
	 * @return mixed
	 */
	function block_inspector_single_panel($settings): mixed {
		$settings['blockInspectorTabs'] = array('default' => false);

		return $settings;
	}


	/**
	 * Only use the block editor for certain content types
	 * @param $current_status
	 * @param $post_type
	 *
	 * @return bool
	 */
	function selective_gutenberg($current_status, $post_type): bool {
		if ($post_type === 'page' || $post_type === 'shared_content' || $post_type === 'post') {
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
