<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::UL, Tag::OL])]
#[DefaultTag(Tag::UL)]
class TabList extends UIComponent {
	use BackgroundColor;

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
		$this->set_background_color_from_attrs($attributes);
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}

		return $attributes;
	}
	
	function render($data = []): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
