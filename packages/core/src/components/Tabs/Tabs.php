<?php
namespace Doubleedesign\Comet\Core;

/**
 * Tabs component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display grouped content in a tabbed interface.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::DIV)]
class Tabs extends PanelGroupComponent {
    use LayoutContainerSize;
    use NestedState;

    /** @var array<TabPanel> */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->set_size($attributes);
        parent::__construct($attributes, $innerComponents, 'components.Tabs.tabs');
        $this->tagName = $this->get_is_nested() ? Tag::DIV : Tag::SECTION;
    }

    public function get_outer_attributes(): array {
        $attributes = [];

        if ($this->size !== null) {
            $attributes['data-size'] = $this->size->value;
        }

        if ($this->get_background_colors()?->outer !== null) {
            $attributes['data-background'] = $this->get_background_colors()?->outer?->value;
        }

        return $attributes;
    }

    public function get_outer_classes(): array {
        return array(
            $this->get_shortname() . '-wrapper'
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'              => $this->tagName->value,
            'outerAttributes'  => $this->get_outer_attributes(),
            'outerClasses'     => $this->get_outer_classes(),
            'classes'          => $this->get_filtered_classes(),
            'attributes'       => $this->get_html_attributes(),
            'panels'           => $this->get_panels(),
        ])->render();
    }
}
