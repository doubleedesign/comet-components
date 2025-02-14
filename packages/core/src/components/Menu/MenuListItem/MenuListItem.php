<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class MenuListItem extends UIComponent {
	/**
	 * @param array $attributes
	 * @param array<Link|MenuList> $innerComponents
	 */
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Menu.MenuListItem.menu-list-item');
    }

	protected function get_bem_name(): ?string {
		// Lil hack to fix the BEM name when there is context because it's not *quite* working as designed
		if(isset($this->context)) {
			return "{$this->context}__item";
		}

		return parent::get_bem_name();
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
