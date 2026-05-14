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

    public function update_context($context): static {
        if ($this->get_shortname() === 'sub-menu') {
            $conciseContext = str_replace('__list__item', '', $context);
            parent::update_context($conciseContext);

            return $this;
        }

        parent::update_context($context);

        return $this;
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
