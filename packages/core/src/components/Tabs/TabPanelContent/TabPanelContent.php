<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class TabPanelContent extends UIComponent {

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Tabs.TabPanelContent.tab-panel-content');
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// Render the children directly without wrapping this component with its own tag because that would be redundant
		echo $blade->make($this->bladeFile, [
			'children' => $this->innerComponents
		])->render();
	}
}
