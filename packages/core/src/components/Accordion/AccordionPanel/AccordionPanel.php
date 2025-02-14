<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DETAILS])]
#[DefaultTag(Tag::DETAILS)]
class AccordionPanel extends UIComponent {

	/** @var array<AccordionPanelTitle|AccordionPanelContent> */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'accordion']),
			$innerComponents,
			'components.Accordion.AccordionPanel.accordion-panel'
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
