<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::UL])]
#[DefaultTag(Tag::UL)]
class MenuList extends UIComponent {
    /**
     * @param  array  $attributes
     * @param  array<MenuListItem>  $menuItems
     */
    public function __construct(array $attributes, array $menuItems) {
        parent::__construct($attributes, $menuItems, 'components.Menu.MenuList.menu-list');
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Hack for overly verbose BEM class names in sub-menus
	    return array_map(function($class) use (&$classes) {
	        if (str_ends_with($class, 'menu__list__item__menu__sub-menu')) {
	            return str_replace('menu__list__item__menu', 'menu__list', $class);
	        }

	        return $class;
	    }, $classes);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
