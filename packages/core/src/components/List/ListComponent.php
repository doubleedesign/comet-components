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
        if (isset($attributes['shortName'])) {
            if (!str_ends_with($attributes['shortName'], '-list')) {
                $attributes['shortName'] .= '-list';
            }
        }
        parent::__construct($attributes, $innerComponents, 'components.List.list');

        if (!str_ends_with($this->get_shortname(), 'list')) {
            $this->set_shortname($this->get_shortname() . '-list');
            array_walk($this->innerComponents, function(&$component) {
                $component->update_context($this->get_shortname());
            });
        }
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
