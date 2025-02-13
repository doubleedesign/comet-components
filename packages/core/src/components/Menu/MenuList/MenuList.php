<?php
namespace Doubleedesign\Comet\Core;

class MenuList extends UIComponent {
	/**
	 * @param array $attributes
	 * @param array<MenuListItem> $menuItems
	 */
	function __construct(array $attributes, array $menuItems) {
		parent::__construct($attributes, $menuItems, 'components.Menu.MenuList.menu-list');
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
