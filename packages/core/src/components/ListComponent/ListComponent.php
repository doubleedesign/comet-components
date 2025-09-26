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
    protected bool $ordered;

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

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'ordered'    => $this->ordered,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
