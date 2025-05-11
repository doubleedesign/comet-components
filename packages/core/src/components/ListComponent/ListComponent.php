<?php
namespace Doubleedesign\Comet\Core;

/**
 * List component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Renders a list of items, either ordered or unordered.
 */
#[AllowedTags([Tag::UL, Tag::OL])]
#[DefaultTag(Tag::UL)]
class ListComponent extends UIComponent {
    private bool $ordered;

    /**
     * @var array<ListItem>
     * @description List item objects to render inside this list
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        $this->ordered = $attributes['ordered'] ?? false;
        $this->tagName = $this->ordered ? Tag::OL : Tag::UL;
        parent::__construct($attributes, $innerComponents, 'components.ListComponent.list');
    }

    public function get_filtered_classes(): array {
        // UIComponent usually adds the BEM class name, but we don't want a class of "list" on every list
        return array_filter(parent::get_filtered_classes(), fn($class) => $class !== $this->shortName);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'ordered'    => $this->ordered,
            'classes'    => implode(',', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
