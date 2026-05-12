<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class MenuListItem extends UIComponent {
    protected bool $isCurrentParent = false;

    /**
     * @param  array  $attributes
     * @param  array<Link|MenuList>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Menu.MenuList.MenuListItem.menu-list-item');
        $this->isCurrentParent = isset($attributes['isCurrentParent']) ? filter_var($attributes['isCurrentParent'], FILTER_VALIDATE_BOOLEAN) : false;
    }

    /**
     * Get the ID of the menu item, generating one if it's empty and requested (e.g., for IDs and aria attributes for submenus and their triggers)
     *
     * @param  bool  $generate_if_empty
     *
     * @return string|null
     */
    public function get_id(bool $generate_if_empty = false): ?string {
        if ($generate_if_empty && empty($this->id)) {
            $this->id = 'menu-item-' . uniqid();
        }
        if (is_numeric($this->id)) {
            $this->id = 'menu-item-' . $this->id;
        }

        return $this->id;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->isCurrentParent) {
            $attributes['data-current-parent'] = "true";
        }

        return $attributes;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Hack for overly verbose BEM class names in sub-menus
        // TODO: Need to somehow fix it in the inner links too
        return array_map(function($class) use (&$classes) {
            if (str_ends_with($class, 'menu__list__item__menu__sub-menu__menu__list__item')) {
                return str_replace('menu__list__item__menu__sub-menu__menu__list', 'menu__list__sub-menu', $class);
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
