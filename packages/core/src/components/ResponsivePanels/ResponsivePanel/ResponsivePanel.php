<?php
namespace Doubleedesign\Comet\Core;

// Note: This component's tag changes responsively,
// determined by the Vue component that renders within ResponsivePanels
#[AllowedTags([Tag::DIV, Tag::DETAILS])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanel extends PanelComponent {

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ResponsivePanels.ResponsivePanel.responsive-panel');
		$this->context = 'responsive';
	}

	public function get_title(): ?array {
		return array(
			'attributes' => [],
			'classes'    => ['responsive-panel__title'],
			'content'    => $this->title . ($this->subtitle ? "<small class='responsive-panel__title__subtitle'>$this->subtitle</small>" : ''),
		);
	}
}
