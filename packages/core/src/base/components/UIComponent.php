<?php
namespace Doubleedesign\Comet\Core;
use RuntimeException;

abstract class UIComponent extends Renderable {
	use HasAllowedTags;

	/**
	 * @var array<Renderable> $innerComponents
	 * @description Inner components to be rendered within this component
	 */
	protected array $innerComponents;

	protected ?Tag $tag = Tag::DIV;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE];
	}

	/**
	 * UIComponent constructor
	 * @param array<string, string|int|array|null> $attributes
	 * @param array<Renderable> $innerComponents
	 * @param string $bladeFile
	 */
	function __construct(array $attributes, array $innerComponents, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->innerComponents = $innerComponents;
		$this->tag = isset($attributes['tagName']) ? Tag::tryFrom($attributes['tagName']) : Tag::DIV;
	}

	/**
	 * Filter the classes to what's valid/supported for this component
	 * plus its name as the first class
	 * @return array<string>
	 */
	protected function get_filtered_classes(): array {
		return array_merge(
			[$this->shortName],
			parent::get_filtered_classes()
		);
	}

	/**
	 * Get the filtered class list for this component as a string
	 * @return string
	 */
	protected function get_filtered_classes_string(): string {
		return implode(' ', $this->get_filtered_classes());
	}

	/**
	 * Process inner blocks to convert them into components
	 * @return array<Renderable>
	 * @throws RuntimeException
	 */
	protected function process_inner_components(): array {
		if(empty($this->innerComponents)) return [];
		$processed_components = [];

		foreach ($this->innerComponents as $component) {
			$name = $component['blockName'] ?? $component['name'];
			if (!$name) {
				throw new RuntimeException('Name of inner component not found, cannot render');
			}

			$ComponentClass = Utils::get_class_name($name);
			if (class_exists($ComponentClass)) {
				$attributes = $component['attrs'] ?? [];
				$innerComponents = $component['innerComponents'] ?? $component['innerBlocks'] ?? null;
				$content = $component['content'] ?? $component['innerHTML'] ?? null;

				// Handle components that have plain text as well as nested components, e.g. list items with nested lists
				// TODO: Ascertain this dynamically
				$canHaveBoth = [__NAMESPACE__ . '\\ListItem'];
				$doesHaveBoth = $innerComponents && !empty(trim($content));
				if (in_array($ComponentClass, $canHaveBoth) && $doesHaveBoth) {
					$componentObject = new $ComponentClass($attributes, $content, $innerComponents);
				}
				else if (!empty($innerComponents) && gettype($innerComponents) === 'array') {
					$componentObject = new $ComponentClass($attributes, $innerComponents);
				}
				else if($content) {
					$componentObject = new $ComponentClass($attributes, $content);
				}
				else {
					$componentObject = new $ComponentClass($attributes); // if we get this far, maybe it's an Image or something else with only attributes at creation time
				}

				$processed_components[] = $componentObject;
			}
		}

		return $processed_components;
	}
}
