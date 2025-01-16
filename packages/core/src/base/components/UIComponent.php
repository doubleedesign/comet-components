<?php
namespace Doubleedesign\Comet\Core;

use RuntimeException;

abstract class UIComponent extends Renderable {
	/**
	 * @var array<Renderable> $innerComponents
	 * @description Inner components to be rendered within this component
	 */
	protected array $innerComponents;

	/**
	 * UIComponent constructor
	 * @param array<string, string|int|array|null> $attributes
	 * @param array<Renderable> $innerComponents
	 * @param string $bladeFile
	 */
	function __construct(array $attributes, array $innerComponents, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->innerComponents = $innerComponents;
	}

	/**
	 * Process inner blocks to convert them into components
	 * @return array<Renderable>
	 * @throws RuntimeException
	 */
	protected function process_inner_components(): array {
		$processed_components = [];

		foreach ($this->innerComponents as $component) {
			$name = $component['blockName'] ?? $component['name'];
			if (!$name) {
				throw new RuntimeException('Name of inner component not found, cannot render');
			}

			$ComponentClass = Utils::get_class_name($name);
			if (class_exists($ComponentClass)) {
				$attributes = $component['attributes'] ?? [];
				$innerComponents = $component['innerComponents'] ?? $component['innerBlocks'] ?? null;
				$content = $component['content'] ?? $component['innerHTML'] ?? null;


				// Handle components that have plain text as well as nested components, e.g. list items with nested lists
				// TODO: Ascertain this dynamically
				$canHaveBoth = [__NAMESPACE__ . '\\ListItem'];
				$doesHaveBoth = $innerComponents && !empty(trim($content));
				if (in_array($ComponentClass, $canHaveBoth) && $doesHaveBoth) {
					$componentObject = new $ComponentClass($attributes, $content, $innerComponents);
				}
				else if (!empty($innerComponents)) {
					$componentObject = new $ComponentClass($attributes, $innerComponents);
				}
				else {
					$componentObject = new $ComponentClass($attributes, $content);
				}

				$processed_components[] = $componentObject;
			}
		}

		return $processed_components;
	}
}
