<?php
namespace Doubleedesign\Comet\Core;

class Breadcrumbs extends UIComponent {
	function __construct(array $attributes, array $breadcrumbs) {
		$listItems = array_map(function($breadcrumb) {
			return new ListItemSimple(['className' => 'breadcrumbs__item'], "<a href=\"{$breadcrumb['url']}\">{$breadcrumb['title']}</a>");
		}, $breadcrumbs);

        parent::__construct($attributes, $listItems, 'components.Breadcrumbs.breadcrumbs');
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
