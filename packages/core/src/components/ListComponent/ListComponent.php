<?php
namespace Doubleedesign\Comet\Core;
use RuntimeException;

class ListComponent extends UIComponent {
	private bool $ordered;

	function __construct(array $attributes, array $innerComponents) {
		$this->ordered = $attributes['ordered'] ?? false;
		$this->tagName = $this->ordered ? Tag::OL : Tag::UL;
		parent::__construct($attributes, $innerComponents, 'components.ListComponent.list');
	}

	function get_inline_styles(): array {
		return [];
	}

	function get_filtered_classes(): array {
		// UIComponent usually adds the BEM class name, but we don't want a class of "list" on every list
		return array_filter(parent::get_filtered_classes(), fn($class) => $class !== $this->shortName);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'ordered'    => $this->ordered,
			'classes'    => implode(',', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
