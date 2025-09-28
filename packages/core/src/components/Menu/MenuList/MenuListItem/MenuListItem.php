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
        $this->isCurrentParent = $attributes['isCurrentParent'] ?? false;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();
        $attributes['data-current-parent'] = $this->isCurrentParent ? 'true' : null;

        return $attributes;
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
