<?php
namespace Doubleedesign\CometCanvas;
use Doubleedesign\Comet\Core\Config;
use WP_Theme_JSON_Data;

class ThemeStyle {
	protected string $embedded_css = '';

	function __construct() {
		add_action('init', [$this, 'set_css_variables_from_theme_json'], 20, 1);
		add_action('wp_head', [$this, 'add_css_variables_to_head'], 25);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_theme_stylesheets'], 20);

		add_action('admin_init', [$this, 'set_css_variables_from_theme_json'], 20, 1);
		add_action('admin_head', [$this, 'add_css_variables_to_head'], 25);

		// Set defaults for components as per Config class in the core package
		add_action('init', [$this, 'set_global_background'], 10);
		add_action('init', [$this, 'set_icon_prefix'], 10);

		if(is_admin()) {
			// using enqueue_block_assets to ensure this runs in the pattern editor iframe (as opposed to enqueue_block_editor_assets)
			// but note that this hook also runs on the front-end, that's why the is_admin() check - or we would double up on styles because of using a bundled stylesheet on the front-end
			add_action('enqueue_block_assets', [$this, 'add_css_variables_to_block_editor'], 25);
			add_action('enqueue_block_assets', [$this, 'enqueue_theme_stylesheets'], 50);
			add_action('enqueue_block_assets', [$this, 'enqueue_editor_specific_css'], 50);
		}
	}

	function set_css_variables_from_theme_json(): void {
		// Note: This needs to run after the same filter in the Comet Components plugin, or the theme object won't be correct
		add_filter('wp_theme_json_data_theme', function(WP_Theme_JSON_Data|\WP_Theme_JSON_Data_Gutenberg $theme_json) {
			$colours = $theme_json->get_data()['settings']['color']['palette']['theme'];
			$gradients = $theme_json->get_data()['settings']['color']['gradients']; // TODO: Implement gradients here
			$css = '';

			if(isset($colours)) {
				foreach($colours as $colourData) {
					$css .= '--color-' . $colourData['slug'] . ': ' . $colourData['color'] . ";\n";
				}
			}

			$this->embedded_css = $css;

			return $theme_json;
		}, 20, 1);
	}

	function add_css_variables_to_head(): void {
		echo '<style>:root {' . $this->embedded_css . '}</style>';
	}

	function add_css_variables_to_block_editor(): void {
		// Attaching this to comet-global-styles ensures it overrides Comet's global.css
		wp_add_inline_style('comet-global-styles', ":root { {$this->embedded_css} }");
	}

	function enqueue_theme_stylesheets(): void {
		$parent = get_template_directory() . '/style.css';
		$child = get_stylesheet_directory() . '/style.css';
		$deps = is_admin() ? array('wp-edit-blocks') : [];

		if(file_exists($parent)) {
			$parent = get_template_directory_uri() . '/style.css';
			wp_enqueue_style('comet-canvas', $parent, $deps, '0.0.2'); // TODO: Get this dynamically
		}

		if(file_exists($child)) {
			$child = get_stylesheet_directory_uri() . '/style.css';
			$theme = wp_get_theme();
			$slug = sanitize_title($theme->get('Name'));

			if(defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') {
				wp_enqueue_style($slug, $child, $deps, time()); // bust cache locally
			}
			else {
				wp_enqueue_style($slug, $child, $deps, $theme->get('Version'));
			}
		}
	}

	function enqueue_editor_specific_css(): void {
		$parent = get_template_directory() . '/editor.css';
		$child = get_stylesheet_directory() . '/editor.css';
		$deps = is_admin() ? array('wp-edit-blocks') : [];

		if(file_exists($parent)) {
			$parent = get_template_directory_uri() . '/editor.css';
			wp_enqueue_style('comet-canvas-editor', $parent, $deps, '0.0.2'); // TODO: Get this dynamically
		}

		if(file_exists($child)) {
			$child = get_stylesheet_directory_uri() . '/editor.css';
			$theme = wp_get_theme();
			$slug = sanitize_title($theme->get('Name')) . '-editor';

			if(defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') {
				wp_enqueue_style($slug, $child, $deps, time()); // bust cache locally
			}
			else {
				wp_enqueue_style($slug, $child, $deps, $theme->get('Version'));
			}
		}
	}

	public function set_global_background(): void {
		$color = apply_filters('comet_canvas_global_background', 'white');
		Config::set_global_background($color);
	}

	public function set_icon_prefix(): void {
		$prefix = apply_filters('comet_canvas_default_icon_prefix', 'fa-solid');
		Config::set_icon_prefix($prefix);
	}
}
