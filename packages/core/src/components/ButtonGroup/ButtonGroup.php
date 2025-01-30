<?php
namespace Doubleedesign\Comet\Core;
use Exception;

class ButtonGroup extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ButtonGroup.button-group');
		$this->tagName = Tag::DIV;
	}

	public function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = $this->get_filtered_classes_string();
		try {
			echo $blade->make($this->bladeFile, [
				'classes'    => $classes,
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e->getMessage(), true));
		}
	}
}
