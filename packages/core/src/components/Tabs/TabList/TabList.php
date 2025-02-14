<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::UL, Tag::OL])]
#[DefaultTag(Tag::UL)]
class TabList extends UIComponent {
	/**
	 * @var array<TabPanelTitle>
	 * @description Items to make up the tab list / links.
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs']),
			$innerComponents,
			'components.Tabs.TabList.tab-list');
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value, // TODO: Implement <ol> option
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
