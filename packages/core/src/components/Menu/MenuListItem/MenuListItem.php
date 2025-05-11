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
        parent::__construct($attributes, $innerComponents, 'components.Menu.MenuListItem.menu-list-item');
        $this->isCurrentParent = $attributes['isCurrentParent'] ?? false;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();
        $attributes['data-current-parent'] = $this->isCurrentParent ? 'true' : null;

        return $attributes;
    }

    protected function get_bem_name(): ?string {
        // Lil hack to fix the BEM name when there is context because it's not *quite* working as designed
        if (isset($this->context)) {
            return "{$this->context}__item";
        }

        return parent::get_bem_name();
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
