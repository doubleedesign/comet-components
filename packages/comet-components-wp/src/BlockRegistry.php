<?php
namespace Doubleedesign\Comet\WordPress;

use RuntimeException;
use Closure;
use WP_Block;
use WP_Block_Type_Registry;

class BlockRegistry extends JavaScriptImplementation {

	function __construct() {
		parent::__construct();
		add_action('init', [$this, 'register_blocks'], 10, 2);
		add_filter('allowed_block_types_all', [$this, 'set_allowed_blocks'], 10, 2);
		add_action('init', [$this, 'override_core_block_rendering'], 20);
		add_action('init', [$this, 'register_core_block_styles'], 10);
		add_filter('block_type_metadata', [$this, 'customise_core_block_options'], 10, 1);
	}

	/**
	 * Get the names of all custom blocks defined in this plugin via JSON files in the blocks folder
	 * @return array
	 */
	function get_custom_block_names(): array {
		$folder = dirname(__DIR__, 1) . '/src/blocks/';
		$files = scandir($folder);
		$blocks = [];
		foreach ($files as $file) {
			$full_path = $folder . $file;
			if (is_file($full_path) && pathinfo($file, PATHINFO_EXTENSION) === 'json') {
				$content = json_decode(file_get_contents($full_path), true);
				if (isset($content['name'])) {
					$blocks[] = $content['name'];
				}
			}
		}

		return $blocks;
	}

	/**
	 * Register custom blocks
	 * // TODO: Add more components as blocks here (e.g., PageHeader, Call-to-Action, etc.)
	 * @return void
	 */
	function register_blocks(): void {
		$names = $this->get_custom_block_names();
		foreach ($names as $name) {
			$shortName = array_reverse(explode('/', $name))[0];
			$file = dirname(__DIR__, 1) . '\src\blocks\\' . $shortName . '.json';
			if (file_exists($file)) {
				register_block_type_from_metadata($file);
			}
		}
	}

	/**
	 * Limit available blocks for simplicity
	 * NOTE: This is not the only place a block may be explicitly allowed.
	 * Most notably, ACF-driven custom blocks and page/post type templates may use/allow them directly.
	 * Some core blocks also have child blocks that already only show up in the right context.
	 *
	 * @param $allowed_blocks
	 *
	 * @return array
	 */
	function set_allowed_blocks($allowed_blocks): array {
		$all_block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
		// Read core block list from JSON file
		$core = json_decode(file_get_contents(__DIR__ . '/block-support.json'), true);
		// Custom blocks in this plugin
		$custom = $this->get_custom_block_names();
		// Third-party plugin block types
		$plugin = array_filter($all_block_types, fn($block_type) => str_starts_with($block_type->name, 'ninja-forms/'));

		$result = array_merge(
			$custom,
			array_column($plugin, 'name'),
			// add core or plugin blocks here if:
			// 1. They are to be allowed at the top level
			// 2. They Are allowed to be inserted as child blocks of a core block (note: set custom parents for core blocks in addCoreBlockParents() in blocks.js if not allowing them at the top level)
			// No need to include them here if they are only being used in one or more of the below contexts:
			// 1. As direct $allowed_blocks within custom ACF-driven blocks and/or
			// 2. In a page/post type template defined programmatically and locked there (so users can't delete something that can't be re-inserted)
			// TODO: When adding post support, there's some post-specific blocks that may be useful
			$core['core']['supported']);

		return $result;
	}

	/**
	 * Utility function to get all allowed blocks after filtering functions have run
	 * @return array
	 */
	function get_allowed_blocks(): array {
		$all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$allowed_blocks = apply_filters('allowed_block_types_all', $all_blocks);

		return array_values($allowed_blocks);
	}


	/**
	 * Get the path to the override template file for a block
	 * TODO: Allow theme to override block output by looking in the theme directory before loading the file from here
	 *
	 * @param $blockName
	 * @return string|null
	 */
	static function get_override_template_file($blockName): ?string {
		$blockNameTrimmed = array_reverse(explode('/', $blockName))[0];
		$file = __DIR__ . '\output\\' . $blockNameTrimmed . '.php';

		return file_exists($file) ? $file : null;
	}


	/**
	 * Override core block render function and use Comet instead
	 * @return void
	 */
	function override_core_block_rendering(): void {
		$blocks = $this->get_allowed_blocks();
		$core_blocks = array_filter($blocks, fn($block) => str_starts_with($block, 'core/'));

		foreach ($core_blocks as $core_block) {
			// Check if the block type and override file exist
			$block_type = WP_Block_Type_Registry::get_instance()->get_registered($core_block);
			$file = $this->get_override_template_file($core_block);

			// If they do, override the render callback
			if ($block_type && $file) {
				// Unregister the original block
				unregister_block_type($core_block);

				// Merge the original block settings with overrides
				$settings = array_merge(
					get_object_vars($block_type), // Convert object to array
					[
						// Custom render callback + pass in the default one for use as a fallback
						'render_callback' => self::render_block_callback($core_block),
					]
				);

				// Re-register the block with the original settings and new render callback
				register_block_type($core_block, $settings);
			}

			// Otherwise, do nothing - the original WP block render function will be used
		}
	}

	/**
	 * Inner function for the override, to render a core block using a custom template
	 * @param $block_name
	 *
	 * @return Closure
	 */
	static function render_block_callback($block_name): Closure {
		return function ($attributes, $content, $block_instance) use ($block_name) {
			return self::render_block($block_name, $attributes, $content, $block_instance);
		};
	}

	/**
	 * The function called inside render_block_callback
	 *
	 * This exists separately for better debugging - this way we see render_block() in Xdebug stack traces,
	 * whereas if this returned the closure directly, it would show up as an anonymous function
	 * @param string $block_name
	 * @param array $attributes
	 * @param string $content
	 * @param WP_Block $block_instance
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	static function render_block(string $block_name, array $attributes, string $content, WP_Block $block_instance): string {
		$block_name_trimmed = explode('/', $block_name)[1];
		$inner_blocks = $block_instance->parsed_block['innerBlocks'];

		// For group block, detect variation based on layout attributes
		if ($block_name_trimmed === 'group') {
			$layout = $attributes['layout'];
			if ($layout['type'] === 'flex') {
				if (isset($layout['orientation']) && $layout['orientation'] === 'vertical') {
					$variation = 'stack';
				}
				else {
					$variation = 'row';
				}
			}
			else if ($layout['type'] === 'grid') {
				$variation = 'grid';
			}
			else {
				$variation = 'group';
			}

			$block_name_trimmed = $variation;
		}

		$file = self::get_override_template_file($block_name);

		// TODO: Check if the file exists in the child theme, then in the parent theme, before defaulting to the plugin
		// Note: Inner block files will not be used here, as they are rendered by the parent block.
		// Maybe I could remove this file entirely and create the component objects right here instead?
		if (file_exists($file)) {
			ob_start();
			// The variables are extracted to make them available to the included file
			extract(['attributes' => $attributes, 'content' => $content, 'innerComponents' => $inner_blocks]);
			// Include the file that renders the block
			include $file;
			return ob_get_clean();
		}

		return '';
	}


	/**
	 * Register additional styles for core blocks
	 * @return void
	 */
	function register_core_block_styles(): void {
		register_block_style('core/paragraph', [
			'name'  => 'lead',
			'label' => 'Lead',
		]);
		register_block_style('core/heading', [
			'name'  => 'accent',
			'label' => 'Accent font',
		]);
		register_block_style('core/heading', [
			'name'  => 'small',
			'label' => 'Small text',
		]);
	}

	/**
	 * Override core block.json configuration
	 * Note: $metadata['allowed_blocks'] also exists and is an array of block names,
	 * so presumably allowed blocks can be added and removed here too
	 * @param $metadata
	 * @return array
	 */
	function customise_core_block_options($metadata): array {
		delete_transient('wp_blocks_data'); // clear cache
		$name = $metadata['name'];

		// Remove support for some things from all blocks
		if (isset($metadata['supports'])) {
			$metadata['supports'] = array_diff_key(
				$metadata['supports'],
				array_flip(['html', 'spacing', 'typography', 'shadow', 'dimensions'])
			);
		}
		if (isset($metadata['attributes'])) {
			$metadata['attributes'] = array_diff_key(
				$metadata['attributes'],
				array_flip(['isStackedOnMobile'])
			);
		}

		// Typography blocks
		if (in_array($name, ['core/heading', 'core/paragraph', 'core/list', 'core/list-item'])) {
			$metadata['supports']['color']['background'] = false;
			$metadata['supports']['color']['gradients'] = false;
			$metadata['supports']['color']['__experimentalDefaultControls'] = [
				'text' => true
			];
		}

		if ($name === 'core/buttons') {
			$metadata['supports']['layout'] = array_merge(
				$metadata['supports']['layout'],
				[
					'allowEditing'           => true, // allow selection of the enabled layout options
					'allowSwitching'         => false,
					'allowOrientation'       => false, // disable vertical stacking option
					'allowJustification'     => true,
					'allowVerticalAlignment' => false
				]
			);
		}
		if ($name === 'core/button') {
			$metadata['attributes'] = array_diff_key(
				$metadata['attributes'],
				array_flip(['textAlign', 'textColor', 'width'])
			);

			$metadata['supports']['color']['text'] = false;
			$metadata['supports']['color']['gradients'] = false;
			$metadata['supports']['__experimentalBorder'] = false;
			$metadata['supports']['color']['__experimentalDefaultControls'] = [
				'background' => true
			];
		}

		// Group block
		// Remember: Row, Stack, and Grid are variations of Group so any settings here will affect all of those
		if ($name === 'core/group') {
			$metadata['supports'] = array_diff_key(
				$metadata['supports'],
				array_flip(['__experimentalSettings', 'align', 'background', 'color', 'position'])
			);

			$metadata['supports']['layout'] = array_merge(
				$metadata['supports']['layout'],
				[
					'allowEditing'           => true, // allow selection of the enabled layout options
					'allowSwitching'         => false, // disables selection of flow/flex/constrained/grid because we're deciding that with CSS
					'allowOrientation'       => false, // disable vertical stacking option
					'allowJustification'     => true,
					'allowVerticalAlignment' => true,
				]
			);
		}

		// Columns
		if ($name === 'core/columns' && isset($metadata['supports']['layout'])) {
			$metadata['supports']['layout'] = array_merge(
				$metadata['supports']['layout'],
				[
					'allowEditing'           => true, // allow selection of the enabled layout options
					'allowSwitching'         => false, // disables selection of flow/flex/constrained/grid because we're deciding that with CSS
					'allowOrientation'       => false, // disable vertical stacking option
					'allowJustification'     => true, // allow selection of horizontal alignment
					'allowVerticalAlignment' => false, // prevent double-up - this one adds a class, but the attribute is an attribute which is preferred for my programmatic handling
				]
			);
		}
		if ($name === 'core/column') {
			if (!is_array($metadata['supports']['layout'])) {
				$metadata['supports']['layout'] = [];
			}
			$metadata['supports']['layout'] = array_merge(
				$metadata['supports']['layout'],
				[
					'allowEditing'           => false,
					'allowInheriting'        => false,
					'allowSwitching'         => false,
					'allowJustification'     => false,
					'allowVerticalAlignment' => false // also use the attribute here, don't add a class name
				]
			);
		}

		return $metadata;
	}
}
