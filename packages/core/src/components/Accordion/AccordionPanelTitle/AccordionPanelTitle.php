<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SUMMARY])]
#[DefaultTag(Tag::SUMMARY)]
class AccordionPanelTitle extends TextElement {

	function __construct(array $attributes, string $content) {
		parent::__construct(
			array_merge($attributes, ['context' => 'accordion__panel']),
			$content, '
			components.Accordion.AccordionPanelTitle.accordion-panel-title'
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// The template just renders the inner content because the rest is taken care of by Vue
		echo $blade->make($this->bladeFile, [
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
