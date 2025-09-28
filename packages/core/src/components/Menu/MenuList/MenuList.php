<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::UL])]
#[DefaultTag(Tag::UL)]
class MenuList extends UIComponent {
    /**
     * @param  array  $attributes
     * @param  array<MenuListItem>  $menuItems
     * @param  string  $bladeFile
     */
    public function __construct(array $attributes, array $menuItems, string $bladeFile = 'components.Menu.MenuList.menu-list') {
        parent::__construct($attributes, $menuItems, $bladeFile);
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
