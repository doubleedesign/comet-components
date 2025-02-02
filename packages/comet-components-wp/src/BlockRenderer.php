<?php
namespace Doubleedesign\Comet\WordPress;
use Doubleedesign\Comet\Core\NotImplemented;
use Doubleedesign\Comet\Core\Utils;
use DOMDocument;
use HTMLPurifier;
use HTMLPurifier_Config;
use ReflectionClass, ReflectionProperty, Closure, ReflectionException;
use WP_Block_Type_Registry, WP_Block;

class BlockRenderer {
	private static array $theme_json;

	public function __construct() {
		// TODO: If the theme has its own, get it from there first
		self::$theme_json = json_decode(file_get_contents(__DIR__ . '/theme.json'), true);

		add_action('init', [$this, 'override_core_block_rendering'], 20);
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
					// Custom front-end rendering using Comet
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
		// Process all inner image blocks and add the relevant attributes as Comet expects
		// self::add_attributes_to_image_blocks($innerComponents); // TODO is this still needed and does it still work?

		// For variant children in context, prepend the variant name to the block name so the correct component will be found
		// e.g. Panel in an Accordion = AccordionPanel
		// $attributes['variant'] indicates we are at the top level
		// TODO Update this
//		if (isset($innerComponents) && isset($attributes['variant'])) {
//			self::apply_variant_context($attributes['variant'], $innerComponents);
//		}

		ob_start();
		$component = self::block_to_comet_component_object($block_instance);
		$component->render();
		return ob_get_clean();
	}

	/**
	 * Get the Comet class name from a block name and see if that class exists
	 * @param string $blockName
	 * @return string|null
	 */
	static function get_comet_component_class(string $blockName): ?string {
		$blockNameTrimmed = array_reverse(explode('/', $blockName))[0];
		$className = Utils::get_class_name($blockNameTrimmed);

		if (class_exists($className)) {
			return $className;
		}

		return null;
	}

	/**
	 * Convert a WP_Block instance to a Comet component object
	 * @param WP_Block $block_instance
	 * @return object|null
	 */
	private static function block_to_comet_component_object(WP_Block $block_instance): ?object {
		$block_name = $block_instance->name;
		$block_name_trimmed = array_reverse(explode('/', $block_name))[0];
		$attributes = $block_instance->attributes ?? [];
		$content = $block_instance->parsed_block['innerHTML'] ?? '';
		$innerComponents = $block_instance->inner_blocks ? self::process_innerblocks($block_instance) : [];

		// Block-specific handling of attributes and content
		if ($block_name === 'core/button') {
			$attributes = self::process_button_block($block_instance)['attributes'];
			$content = self::process_button_block($block_instance)['content'];
		}
		if ($block_name === 'core/image') {
			self::process_image_block($block_instance);
		}

		// Figure out the component class to use:
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
		// For the core group block, detect variation based on layout attributes and use that class instead
		else if ($block_name_trimmed === 'group') {
			$layout = $attributes['layout'];
			$variation = match ($layout['type']) {
				'flex' => isset($layout['orientation']) && $layout['orientation'] === 'vertical' ? 'stack' : 'row',
				'grid' => 'grid',
				default => 'group'
			};
			$ComponentClass = self::get_comet_component_class($variation);
		}
		// This is a regular block that is not a Group or variation of a Group
		else {
			$ComponentClass = self::get_comet_component_class($block_name); // returns the namespaced class name matching the block name
		}

		// Check what type of content to pass to it - an array, a string, etc
		$content_type = self::get_comet_component_content_type($ComponentClass);

		// Create the component object
		// Self-closing tag components, e.g. <img>, only have attributes
		if ($content_type[0] === 'is-self-closing') {
			$component = new $ComponentClass($attributes);
		}
		// Most components will have string content or an array of inner components
		else if (count($content_type) === 1) {
			$component = $content_type[0] === 'array' ? new $ComponentClass($attributes, $innerComponents) : new $ComponentClass($attributes, $content);
		}
		// Some can have both, e.g. list items can have text content and nested lists
		else if (count($content_type) === 2) {
			$component = new $ComponentClass($attributes, $content, $innerComponents);
		}

		return $component ?? null;
	}

	/**
	 * Convert an innerBlocks array to an array of Comet component objects
	 * @param WP_Block $block_instance
	 * @return array|null
	 */
	private static function process_innerblocks(WP_Block $block_instance): ?array {
		$innerBlocks = $block_instance->inner_blocks ?? null;
		if ($innerBlocks) {
			return array_map(function ($block) {
				return self::block_to_comet_component_object($block);
			}, iterator_to_array($innerBlocks));
		}

		return null;
	}

	/**
	 * Check whether the Comet Component's render method has been implemented
	 * Helpful for development/progressive adoption of components by falling back to default WP rendering if the custom one isn't ready yet
	 * @param string $className
	 * @return bool
	 */
	public static function can_render_comet_component(string $className): bool {
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
	 * Check if the class is expecting string $content, an array of $innerComponents, both, or neither
	 * This is used in the block render function to determine what to pass from WordPress to the Comet component
	 * because Comet has constructors like new Thing($attributes, $content) or new Thing($attributes, $innerComponents)
	 * @param string $className
	 * @return array<string>|null
	 */
	private static function get_comet_component_content_type(string $className): ?array {
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
	 * Process all image blocks and add the relevant attributes as Comet expects
	 * @param WP_Block $block_instance
	 * @return void
	 */
	static function process_image_block(WP_Block &$block_instance): void {
		if(!isset($block['attrs']['id'])) return;

		$size = $attrs['sizeSlug'] ?? 'full';
		$block_instance['attrs']['src'] = wp_get_attachment_image_url($block_instance['attrs']['id'], $size);
		$block_instance['attrs']['alt'] = get_post_meta($block_instance['attrs']['id'], '_wp_attachment_image_alt', true) ?? '';
		$block_instance['attrs']['caption'] = wp_get_attachment_caption($block_instance['attrs']['id']) ?? null;
		$block_instance['attrs']['title'] = get_the_title($block_instance['attrs']['id']) ?? null;

		$block_content = $block['innerHTML'];
		preg_match('/href="([^"]+)"/', $block_content, $matches);
		$block_instance['attrs']['href'] = $matches[1] ?? null;
	}

	/**
	 * Process the button block's HTML and turn it into Comet-compatible attributes format
	 * @param WP_Block $block_instance
	 * @return array
	 */
	static function process_button_block(WP_Block $block_instance): array {
		$attributes = $block_instance->attributes;
		$raw_content = $block_instance->parsed_block['innerHTML'];
		$content = '';

		// Process custom attributes
		if(isset($attributes['style'])) {
			$attributes['colorTheme'] = self::hex_to_theme_color_name($attributes['style']['elements']['theme']['color']['text']) ?? null;
			unset($attributes['style']);
		}

		// Turn style classes into attributes
		if(isset($attributes['className'])) {
			$classes = explode(' ', $attributes['className']);
			if (in_array('is-style-outline', $classes)) {
				$attributes['isOutline'] = true;
			}
		}

		// Use HTMLPurifier to do the initial stripping of unwanted tags and attributes for the inner content
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'a[href|target|title|rel],span,i,b,strong,em');
		$config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_parent', '_top']);
		$config->set('HTML.TargetBlank', true);
		$purifier = new HTMLPurifier($config);
		$clean_html = $purifier->purify($raw_content);

		// Create a simple DOM parser and find the anchor tag and attributes
		// Note: In PHP 8.4+ you will be able to use Dom\HTMLDocument::createFromString and presumably remove the ext-dom and ext-libxml Composer dependencies
		$dom = new DOMDocument();
		$dom->loadHTML($clean_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$links = $dom->getElementsByTagName('a');
		$link = $links->item(0);
		foreach ($link->attributes as $attr) {
			$attributes[$attr->name] = $attr->value;
		}

		// Remove unwanted attributes
		unset($attributes['type']);

		// Get inner HTML with any nested tags
		foreach ($link->childNodes as $child) {
			$content .= $dom->saveHTML($child);
		}

		return [
			'attributes' => $attributes,
			'content'    => $content
		];
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
	private static function apply_variant_context($variant_name, &$blocks): void {
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
	 * The custom colour attributes are stored as hex values, but we want them as the theme colour names
	 * @param $hex
	 * @return string | null
	 */
	private static function hex_to_theme_color_name($hex): ?string {
		$theme = self::$theme_json['settings']['color']['palette'];

		return array_reduce($theme, function ($carry, $item) use ($hex) {
			return $item['color'] === $hex ? $item['slug'] : $carry;
		}, null);
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

}
