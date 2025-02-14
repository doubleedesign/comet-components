<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class AccordionPanelContent extends UIComponent {

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'accordion__panel']),
			$innerComponents,
			'components.Accordion.AccordionPanelContent.accordion-panel-content'
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
