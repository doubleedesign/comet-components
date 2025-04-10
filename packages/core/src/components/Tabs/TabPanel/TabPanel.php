<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class TabPanel extends PanelComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Tabs.TabPanel.tab-panel');
		$this->context = 'tabs';
	}

	public function get_title(): ?array {
		return array(
			'attributes' => [],
			'classes' => ['tabs__tab-list__item'],
			'content' => "$this->title" . ($this->subtitle ? "<small class='tabs__tab-list__item__subtitle'>$this->subtitle</small>" : ''),
		);
	}
}
