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

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
