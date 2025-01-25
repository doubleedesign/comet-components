<?php
namespace Doubleedesign\Comet\Core;
use Exception;

class AccordionPanelContent extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Accordion.AccordionPanelContent.accordion-panel-content');
	}

	function get_inline_styles(): array {
		return [];
	}

	function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();

		try {
			echo $blade->make($this->bladeFile, [
				'classes'    => $this->get_filtered_classes_string(),
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e, true));
		}
	}
}
