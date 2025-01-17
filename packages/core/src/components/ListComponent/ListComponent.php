<?php
namespace Doubleedesign\Comet\Core;
use RuntimeException;

class ListComponent extends UIComponent {
	private bool $ordered;

	function __construct(array $attributes, array $innerComponents) {
		$this->ordered = $attributes['ordered'] ?? false;
		$this->tag = $this->ordered ? Tag::OL : Tag::UL;
		parent::__construct($attributes, $innerComponents, 'components.ListComponent.list');
	}

	function get_inline_styles(): array {
		return [];
	}

	function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = implode(',', $this->get_filtered_classes());

		try {
			echo $blade->make($this->bladeFile, [
				'ordered'    => $this->ordered,
				'classes'    => $classes,
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (RuntimeException $e) {
			error_log(print_r($e, true));
		}
	}
}
