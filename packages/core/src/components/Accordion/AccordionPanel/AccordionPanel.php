<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DETAILS])]
#[DefaultTag(Tag::DETAILS)]
class AccordionPanel extends PanelComponent {

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Accordion.AccordionPanel.accordion-panel');
		$this->context = 'accordion';
	}

	protected function get_bem_name(): ?string {
		return 'accordion__panel__content';
	}

	public function get_title(): ?array {
		return array(
			'attributes' => [],
			'classes' => ['accordion__panel__title'],
			'content' => "$this->title" . ($this->subtitle ? "<small class='accordion__panel__title__subtitle'>$this->subtitle</small>" : ''),
		);
	}
}
