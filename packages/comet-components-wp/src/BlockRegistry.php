<?php
namespace Doubleedesign\Comet\WordPress;
use Doubleedesign\Comet\Core\{Utils, NotImplemented};
use ReflectionClass, ReflectionProperty, Closure, ReflectionException, Exception, TypeError;
use WP_Block, WP_Block_Type_Registry;
use Block_Supports_Extended;

class BlockRegistry extends JavaScriptImplementation {
	private array $block_support_json;

	function __construct() {
		parent::__construct();

		$this->block_support_json = json_decode(file_get_contents(__DIR__ . '/block-support.json'), true);

		add_action('init', [$this, 'register_blocks'], 10, 2);
		add_action('acf/include_fields', [$this, 'register_block_fields'], 10, 2);
		add_filter('allowed_block_types_all', [$this, 'set_allowed_blocks'], 10, 2);
		add_action('init', [$this, 'override_core_block_rendering'], 20);
		add_action('init', [$this, 'register_core_block_styles'], 10);
		add_action('block_type_metadata', [$this, 'register_core_block_variations'], 10);
		add_action('block_type_metadata', [$this, 'update_some_core_block_descriptions'], 10);
		add_action('init', [$this, 'register_custom_attributes'], 5);
		add_filter('block_type_metadata', [$this, 'customise_core_block_options'], 15, 1);
		add_filter('block_type_metadata', [$this, 'control_block_parents'], 15, 1);
	}

	/**
	 * Get the names of all custom blocks defined in this plugin via JSON files in the blocks folder
	 * @return array
	 */
	function get_custom_block_names(): array {
		$folder = dirname(__DIR__, 1) . '/src/blocks/';
		$block_folders = scandir($folder);
		$blocks = array_map(fn($block) => 'comet/' . $block, $block_folders);

		return $blocks;
	}

	/**
	 * Register custom blocks
	 * @return void
	 */
	function register_blocks(): void {
		$block_folders = scandir(dirname(__DIR__, 1) . '/src/blocks');

		foreach ($block_folders as $block_name) {
			$folder = dirname(__DIR__, 1) . '/src/blocks/' . $block_name;
			$className = self::get_comet_component_class($block_name);
			if (!file_exists("$folder/block.json")) continue;

			$block_json = json_decode(file_get_contents("$folder/block.json"));

			// Block name -> direct translation to component name
			if (isset($className) && $this->can_render_comet_component($className)) {
				register_block_type($folder, [
					'render_callback' => self::render_block_callback("comet/$block_name")
				]);

				$shortName = $block_name; // folder name in this case
				$pascalCaseName = Utils::pascal_case($shortName); // should match the class name without the namespace
				$cssPath = dirname(__DIR__, 1) . "/vendor/doubleedesign/comet-components-core/src/components/$pascalCaseName/$shortName.css";
				if (file_exists($cssPath)) {
					$handle = Utils::kebab_case($block_name) . '-style';
					$pluginFilePath = dirname(plugin_dir_url(__FILE__)) . "/vendor/doubleedesign/comet-components-core/src/components/$pascalCaseName/$shortName.css";
					wp_register_style(
						$handle,
						$pluginFilePath,
						[],
						COMET_VERSION
					);

					wp_enqueue_style($handle);
				}

			}
			// Block has variations that align to a component name, without the overarching block name being used for a rendering class
			else if (isset($block_json->variations)) {
				// TODO: Actually check for matching component classes
				register_block_type($folder, [
					'render_callback' => self::render_block_callback("comet/$block_name")
				]);

				foreach ($block_json->variations as $variation) {
					$shortName = Utils::pascal_case(array_reverse(explode('/', $variation->name))[0]);
					$shortNameLower = strtolower($shortName);
					$filePath = dirname(__DIR__, 1) . "/vendor/doubleedesign/comet-components-core/src/components/$shortName/$shortNameLower.css";

					if (file_exists($filePath)) {
						$handle = Utils::kebab_case($variation->name) . '-style';
						$pluginFilePath = dirname(plugin_dir_url(__FILE__)) . "/vendor/doubleedesign/comet-components-core/src/components/$shortName/$shortNameLower.css";
						wp_register_style(
							$handle,
							$pluginFilePath,
							[],
							COMET_VERSION
						);

						wp_enqueue_style($handle);
					}
				}

			}
			// Block is an inner component of a variation, and we want to use a Comet Component according to the variation
			else if (isset($block_json->parent)) {
				// TODO: Actually check for matching component classes
				register_block_type($folder, [
					'render_callback' => self::render_block_callback("comet/$block_name")
				]);
			}
			// Fallback = WP block rendering
			else {
				register_block_type($folder);
			}
		}
	}

	/**
	 * Register ACF fields for custom blocks if there is a fields.php file containing them in the block folder
	 * @return void
	 */
	function register_block_fields(): void {
		$block_folders = scandir(dirname(__DIR__, 1) . '/src/blocks');

		foreach ($block_folders as $block_name) {
			$file = dirname(__DIR__, 1) . '/src/blocks/' . $block_name . '/fields.php';

			if (file_exists($file) && function_exists('acf_add_local_field_group')) {
				require_once $file;
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
		$core = array_keys($this->block_support_json['core']['supported']);
		// Custom blocks in this plugin
		$custom = $this->get_custom_block_names();
		// Third-party plugin block types
		$plugin = array_filter($all_block_types, fn($block_type) => str_starts_with($block_type->name, 'ninja-forms/'));

		$result = array_merge(
			$core,
			$custom,
			array_column($plugin, 'name')
		// add core or plugin blocks here if:
		// 1. They are to be allowed at the top level
		// 2. They Are allowed to be inserted as child blocks of a core block (note: set custom parents for core blocks in addCoreBlockParents() in blocks.js if not allowing them at the top level)
		// No need to include them here if they are only being used in one or more of the below contexts:
		// 1. As direct $allowed_blocks within custom ACF-driven blocks and/or
		// 2. In a page/post type template defined programmatically and locked there (so users can't delete something that can't be re-inserted)
		// TODO: When adding post support, there's some post-specific blocks that may be useful
		);

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
		return ['is-self-closing']; // Assuming if we get this far, it's something like the Image block
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
	 * Note: Inner blocks do not always hit this code, as they can be rendered by the parent Comet component.
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
		$innerComponents = $block_instance->parsed_block['innerBlocks'];
		// This is a block variant at the top level, such as an Accordion (variant of Panels)
		if (isset($attributes['variant'])) {
			// use the namespaced class name matching the variant name
			$ComponentClass = self::get_comet_component_class($attributes['variant']);
		}
		// This is a block within a variant that is providing its namespaced name via the providesContext property
		else if (isset($block_instance->context['comet/variant'])) {
			// use the namespaced class name matching the variant name + the block name (e.g. Accordion variant + Panel block = AccordionPanel)
			$variant = $block_instance->context['comet/variant'];
			$transformed_name = Utils::pascal_case("$variant-$block_name_trimmed");
			$ComponentClass = self::get_comet_component_class($transformed_name);
		}
		// This is a regular block
		else {
			$ComponentClass = self::get_comet_component_class($block_name); // returns the namespaced class name matching the block name
		}

		// For the core group block, detect variation based on layout attributes
		if ($block_name_trimmed === 'group') {
			$layout = $attributes['layout'];
			$variation = match ($layout['type']) {
				'flex' => isset($layout['orientation']) && $layout['orientation'] === 'vertical' ? 'stack' : 'row',
				'grid' => 'grid',
				default => 'group'
			};
			$ComponentClass = self::get_comet_component_class($variation);
		}

		// so we know what to pass to it - an array, a string, etc
		$content_type = self::get_comet_component_content_type($ComponentClass);

		// For variant children in context, prepend the variant name to the block name so the correct component will be found
		// e.g. Panel in an Accordion = AccordionPanel
		// $attributes['variant'] indicates we are at the top level
		if (isset($innerComponents) && isset($attributes['variant'])) {
			self::apply_variant_context($attributes['variant'], $innerComponents);
		}

		// If this is an image block, add the src attribute
		if ($block_name === 'core/image') {
			$attributes['src'] = wp_get_attachment_image_url($attributes['id'], 'full');
		}
		// Process all inner image blocks and add the relevant attributes as Comet expects
		self::add_attributes_to_image_blocks($innerComponents);

		// Prepare the output
		ob_start();
		extract(['attributes' => $attributes, 'content' => $content, 'innerComponents' => $innerComponents]);

		// Self-closing tag components, e.g. <img>, only have attributes
		if ($content_type[0] === 'is-self-closing') {
			try {
				$component = new $ComponentClass($attributes);
				$component->render();
			}
			catch (TypeError|Exception $e) {
				error_log(print_r($e, true));
			}
		}
		// Most components will have string content or an array of inner components
		else if (count($content_type) === 1) {
			try {
				$component = $content_type[0] === 'array' ? new $ComponentClass($attributes, $innerComponents) : new $ComponentClass($attributes, $content);
				$component->render();
			}
			catch (TypeError|Exception $e) {
				error_log(print_r($e, true));
			}
		}
		// Some can have both, e.g. list items can have text content and nested lists
		else if (count($content_type) === 2) {
			try {
				$component = new $ComponentClass($attributes, $content, $innerComponents);
				$component->render();
			}
			catch (TypeError|Exception $e) {
				error_log(print_r($e, true));
			}
		}

		return ob_get_clean();
	}


	/**
	 * Loop through an array of inner blocks and prepend the given variant name to the block name,
	 * and add an attribute to pass down the variant context in the way Comet expects
	 * e.g. comet/panel => comet/accordion-panel (where accordion is the variant name)
	 * $blocks are passed by reference and are modified in-place rather than returning a new array
	 * @param $variant_name
	 * @param $blocks
	 * @return void
	 */
	static function apply_variant_context($variant_name, &$blocks): void {
		foreach ($blocks as &$block) {
			if (!str_starts_with($block['blockName'], 'comet/')) return; // Apply only to Comet component blocks

			$short_name = explode('/', $block['blockName'])[1];
			$block['blockName'] = "comet/$variant_name-$short_name";
			$block['attrs']['context'] = $variant_name;

			// Recurse into inner blocks
			if (isset($block['innerBlocks'])) {
				self::apply_variant_context($variant_name, $block['innerBlocks']);
			}
		}
	}

	/**
	 * Because the render_block function only renders the top level and the Image component needs the src attribute,
	 * we need to process innerBlocks and add it
	 * @param array $blocks
	 * @return void
	 */
	static function add_attributes_to_image_blocks(array &$blocks): void {
		foreach ($blocks as &$block) {
			if ($block['blockName'] === 'core/image' && isset($block['attrs']['id'])) {
				$size = $attrs['sizeSlug'] ?? 'full';
				$block['attrs']['src'] = wp_get_attachment_image_url($block['attrs']['id'], $size);
				$block['attrs']['alt'] = get_post_meta($block['attrs']['id'], '_wp_attachment_image_alt', true) ?? '';
				$block['attrs']['caption'] = wp_get_attachment_caption($block['attrs']['id']) ?? null;
				$block['attrs']['title'] = get_the_title($block['attrs']['id']) ?? null;

				$block_content = $block['innerHTML'];
				preg_match('/href="([^"]+)"/', $block_content, $matches);
				$block['attrs']['href'] = $matches[1] ?? null;
			}

			// Recurse into inner blocks
			if (isset($block['innerBlocks'])) {
				self::add_attributes_to_image_blocks($block['innerBlocks']);
			}
		}
	}


	/**
	 * Register custom variations of core block
	 * @param $metadata - the existing block metadata used to register it
	 * @return array
	 */
	function register_core_block_variations($metadata): array {
		$supported_core_blocks = $this->block_support_json['core']['supported'];
		$blocks_with_variations = array_filter($supported_core_blocks, fn($block) => isset($block['variations']));

		foreach ($blocks_with_variations as $block_name => $data) {
			if ($metadata['name'] === $block_name) {
				$metadata['variations'] = array_merge(
					$metadata['variations'] ?? array(),
					$data['variations']
				);
			}
		}

		return $metadata;
	}


	function update_some_core_block_descriptions($metadata): array {
		$blocks = $this->block_support_json['core']['supported'];
		$blocks_to_update = array_filter($blocks, fn($block) => isset($block['description']));

		foreach ($blocks_to_update as $block_name => $data) {
			if ($metadata['name'] === $block_name) {
				$metadata['description'] = $data['description'];
			}
		}

		return $metadata;
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
	 * Register some additional attribute options
	 * Note: Requires the block-supports-extended plugin, which is installed as a dependency via Composer
	 * @return void
	 */
	function register_custom_attributes(): void {
//		Block_Supports_Extended\register('color', 'inner_background', [
//			'label'  => __('Inner background'),
//			'blocks' => ['core/group', 'core/columns'],
//		]);

		Block_Supports_Extended\register('color', 'theme', [
			'label'  => __('Colour theme'),
			'blocks' => ['comet/panels'],
		]);

		// Note: Remove the thing the custom attribute is replacing, if applicable, using block_type_metadata filter
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
				array_flip(['spacing', 'typography', 'shadow', 'dimensions'])
			);
		}
		if (isset($metadata['attributes']['isStackedOnMobile'])) {
			$metadata['attributes'] = array_diff_key(
				$metadata['attributes'],
				array_flip(['isStackedOnMobile'])
			);
		}

		// All layout blocks
		if (in_array($name, $layout_blocks)) {
			$metadata['supports']['color']['background'] = true;
			$metadata['supports']['color']['gradients'] = false;
			$metadata['supports']['color']['text'] = false;
			$metadata['supports']['color']['__experimentalDefaultControls'] = [
				'background' => true,
				'text'       => false,
			];
		}

		// All typography blocks
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
				array_flip(['__experimentalSettings', 'align', 'position'])
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
		if ($name === 'core/columns') {
			$metadata['attributes']['tagName'] = [
				'type'    => 'string',
				'default' => 'div'
			];
		}
		if ($name === 'core/columns' && isset($metadata['supports']['layout'])) {
			$metadata['supports']['layout'] = array_merge(
				$metadata['supports']['layout'],
				[
					'allowEditing'           => true,  // allow selection of any enabled layout options
					'allowSwitching'         => false, // selection of flow/flex/constrained/grid - false because we're deciding that with CSS
					'allowOrientation'       => false, // vertical stacking option
					'allowJustification'     => false, // selection of horizontal alignment
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

	/**
	 * Control where blocks can be placed by requiring them to be inside certain other blocks
	 * This is mainly for core blocks because custom blocks should have the parent set in block.json,
	 * but because this uses block-support.json's categories then these settings can also apply to custom blocks that are listed there
	 * @param $metadata
	 * @return array
	 */
	function control_block_parents($metadata): array {
		delete_transient('wp_blocks_data'); // clear cache
		$name = $metadata['name'];

		$typography_blocks = array_values(array_filter(
			$this->block_support_json['categories'],
			fn($category) => $category['slug'] === 'text'
		))[0]['blocks'] ?? [];

		$layout_blocks = array_values(array_filter(
			$this->block_support_json['categories'],
			fn($category) => $category['slug'] === 'design'
		))[0]['blocks'] ?? [];
		$layout_blocks = array_filter($layout_blocks, fn($block) => $block !== 'core/column');

		$media_blocks = array_values(array_filter(
			$this->block_support_json['categories'],
			fn($category) => $category['slug'] === 'media'
		))[0]['blocks'] ?? [];

		$content_blocks = array_values(array_filter(
			$this->block_support_json['categories'],
			fn($category) => $category['slug'] === 'content'
		))[0]['blocks'] ?? [];

		if (in_array($name, $layout_blocks)) {
			$supported = ['comet/container', 'comet/panel-content'];
			if (isset($metadata['parent'])) {
				$metadata['parent'] = array_merge($metadata['parent'], $supported);
			}
			else {
				$metadata['parent'] = $supported;
			}
		}
		if (in_array($name, array_merge($typography_blocks, $media_blocks))) {
			$supported = ['comet/container', 'core/column', 'core/group'];

			if (isset($metadata['parent'])) {
				$metadata['parent'] = array_merge($metadata['parent'], $supported);
			}
			else {
				$metadata['parent'] = $supported;
			}
		}
		if (in_array($name, array_merge($content_blocks, ['core/embed']))) {
			$supported = ['comet/container', 'core/column', 'core/group', 'core/details', 'comet/panel-content'];

			if (isset($metadata['parent'])) {
				$metadata['parent'] = array_merge($metadata['parent'], $supported);
			}
			else {
				$metadata['parent'] = $supported;
			}
		}
		if ($name === 'core/freeform') {
			$metadata['parent'] = ['comet/container', 'comet/group', 'comet/column', 'comet/panel-content'];
		}

		return $metadata;
	}
}
