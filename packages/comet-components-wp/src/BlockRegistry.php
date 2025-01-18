<?php
namespace Doubleedesign\Comet\WordPress;
use Doubleedesign\Comet\Core\{Utils, NotImplemented};
use ReflectionClass, ReflectionProperty, Closure, ReflectionException;
use WP_Block, WP_Block_Type_Registry;
use Block_Supports_Extended;

class BlockRegistry extends JavaScriptImplementation {
	private array $block_support_json;

	function __construct() {
		parent::__construct();

		$this->block_support_json = json_decode(file_get_contents(__DIR__ . '/block-support.json'), true);

		add_action('init', [$this, 'register_blocks'], 10, 2);
		add_filter('allowed_block_types_all', [$this, 'set_allowed_blocks'], 10, 2);
		add_action('init', [$this, 'override_core_block_rendering'], 20);
		add_action('init', [$this, 'register_core_block_styles'], 10);
		//add_action('init', [$this, 'register_custom_attributes'], 5);
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
		$core = $this->block_support_json;
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
	 * Get the Comet class name from a block name and see if that class exists
	 * @param $blockName
	 * @return string|null
	 */
	static function get_comet_component_class($blockName): ?string {
		$blockNameTrimmed = array_reverse(explode('/', $blockName))[0];
		$className = Utils::get_class_name($blockNameTrimmed);

		if (class_exists($className)) {
			return $className;
		}

		return null;
	}

	/**
	 * Check if the class is expecting string $content, an array of $innerComponents, both, or neither
	 * This is used in the block render function to determine what to pass from WordPress to the Comet component
	 * because Comet has constructors like new Thing($attributes, $content) or new Thing($attributes, $innerComponents)
	 * @param $className
	 * @return string[]|null
	 */
	static function get_comet_component_content_type($className): ?array {
		if (!$className || !class_exists($className)) return null;

		$fields = [];
		$reflectionClass = new ReflectionClass($className);
		$properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

		foreach ($properties as $property) {
			$fields[$property->getName()] = $property->getType()->getName();
		}

		$stringContent = isset($fields['content']) && $fields['content'] === 'string';
		$innerComponents = isset($fields['innerComponents']) && $fields['innerComponents'] === 'array';

		if ($stringContent && $innerComponents) {
			return ['string', 'array'];
		}
		else if ($stringContent) {
			return ['string'];
		}
		else if ($innerComponents) {
			return ['array'];
		}
		return null;
	}

	/**
	 * Check whether the Comet Component's render method has been implemented
	 * Helpful for development/progressive adoption of components by falling back to default WP rendering if the custom one isn't ready yet
	 * @param string $className
	 * @return bool
	 */
	static function can_render_comet_component(string $className): bool {
		try {
			$reflectionClass = new ReflectionClass($className);
			$method = $reflectionClass->getMethod('render');
			$attribute = $method->getAttributes(NotImplemented::class);
			return empty($attribute);
		}
		catch (ReflectionException $e) {
			return false;
		}
	}

	/**
	 * Override core block render function and use Comet instead
	 * @return void
	 */
	function override_core_block_rendering(): void {
		$blocks = $this->get_allowed_blocks();
		$core_blocks = array_filter($blocks, fn($block) => str_starts_with($block, 'core/'));

		// Check rendering prerequisites and bail early if one is not met
		// Otherwise, do nothing - the original WP block render function will be used
		foreach ($core_blocks as $core_block_name) {
			// WP block type exists
			$block_type = WP_Block_Type_Registry::get_instance()->get_registered($core_block_name);
			if (!$block_type) continue;

			// Corresponding Comet Component class exists
			$ComponentClass = self::get_comet_component_class($core_block_name); // returns the namespaced class name
			if (!$ComponentClass) continue;

			//...and the render method has been implemented
			$ready_to_render = self::can_render_comet_component($ComponentClass);
			if (!$ready_to_render) continue;

			//...and one or more of the expected content fields is present
			$content_types = self::get_comet_component_content_type($ComponentClass); // so we know what to pass to it
			if (!$content_types) continue;

			// If all of those conditions were met, override the block's render callback
			// Unregister the original block
			unregister_block_type($core_block_name);

			// Merge the original block settings with overrides
			$settings = array_merge(
				get_object_vars($block_type), // Convert object to array
				[
					// Custom render callback
					'render_callback' => self::render_block_callback($core_block_name),
				]
			);

			// Re-register the block with the original settings and new render callback
			register_block_type($core_block_name, $settings);
		}
	}

	/**
	 * Inner function for the override, to render a core block using a custom template
	 * @param string $block_name
	 *
	 * @return Closure
	 */
	static function render_block_callback(string $block_name): Closure {
		return function ($attributes, $content, $block_instance) use ($block_name) {
			return self::render_block($block_name, $attributes, $content, $block_instance);
		};
	}

	/**
	 * The function called inside render_block_callback
	 * to render blocks using Comet Components.
	 * Note: Inner blocks do not hit this code, as they are rendered by the parent block.
	 *
	 * This exists separately from render_block_callback for better debugging - this way we see render_block() in Xdebug stack traces,
	 * whereas if this returned the closure directly, it would show up as an anonymous function
	 * @param string $block_name
	 * @param array $attributes
	 * @param string $content
	 * @param WP_Block $block_instance
	 *
	 * @return string
	 */
	static function render_block(string $block_name, array $attributes, string $content, WP_Block $block_instance): string {
		$block_name_trimmed = explode('/', $block_name)[1];
		$inner_blocks = $block_instance->parsed_block['innerBlocks'];
		$ComponentClass = self::get_comet_component_class($block_name); // returns the namespaced class name
		$content_type = self::get_comet_component_content_type($ComponentClass); // so we know what to pass to it

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

		ob_start();
		extract(['attributes' => $attributes, 'content' => $content, 'innerComponents' => $inner_blocks]);

		// Most components will have string content or an array of inner components
		if (count($content_type) === 1) {
			$component = $content_type[0] === 'array' ? new $ComponentClass($attributes, $innerComponents) : new $ComponentClass($attributes, $content);
			$component->render();
		}
		// Some can have both, e.g. list items can have text content and nested lists
		else if (count($content_type) === 2) {
			$component = new $ComponentClass($attributes, $content, $innerComponents);
			$component->render();
		}

		return ob_get_clean();
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
	 * Register some additional attributes for core blocks
	 * Note: Requires the block-supports-extended plugin, which is installed as a dependency via Composer
	 * @return void
	 */
	function register_custom_attributes(): void {
		// Theme colour option that matches the field type and structure of the existing background and text fields
		Block_Supports_Extended\register('color', 'theme', [
			'label'    => __('Theme'),
			'property' => 'text',
			// %s is replaced with a generated class name applied to the block.
			'selector' => '.%1$s',
			// Optional list of default blocks to add support for. This can
			// also be done via a block's block.json "supports" property, or
			// later using the Block_Supports_Extended\add_support() function.
			'blocks'   => [
				'core/details',
			],
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

		$typography_blocks = array_values(array_filter(
			$this->block_support_json['categories'],
			fn($category) => $category['slug'] === 'text'
		))[0]['blocks'] ?? null;

		$layout_blocks = array_values(array_filter(
			$this->block_support_json['categories'],
			fn($category) => $category['slug'] === 'design'
		))[0]['blocks'] ?? null;

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
		if (in_array($name, $typography_blocks)) {
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
