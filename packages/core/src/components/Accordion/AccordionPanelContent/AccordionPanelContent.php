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

		// The template just renders the inner content because the rest is taken care of by Vue
		echo $blade->make($this->bladeFile, [
			'children'   => $this->innerComponents
		])->render();
	}
}
