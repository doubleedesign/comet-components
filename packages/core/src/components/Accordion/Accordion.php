<?php
namespace Doubleedesign\Comet\Core;

/**
 * Accordion component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Group content into expandable/collapsible panels.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::DIV)]
class Accordion extends PanelGroupComponent {
    use Icon;
    use LayoutContainerSize;
    use NestedState;

    /**
     * @var ?string $icon
     * @description Icon class name for the icon to use for the expand/collapse indicator.
     */
    protected ?string $icon;

    /**
     * @var array<Renderable> $beforeComponents
     * @description Components to render before the main component (e.g. heading, intro text).
     */
    protected array $beforeComponents = [];

    /**
     * @var array<AccordionPanel> $innerComponents
     * @description Panels to include in the accordion.
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents, ?array $beforeComponents = []) {
        $this->set_icon_from_attrs($attributes, 'fa-plus');
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->set_size($attributes);
        $this->beforeComponents = $beforeComponents ?? [];
        parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
        $this->tagName = $this->get_is_nested() ? Tag::DIV : Tag::SECTION;

        if (!empty($beforeComponents)) {
            $this->beforeComponents = [new Group(
                [
                    'context'         => $this->get_shortname() . '-wrapper',
                    'shortName'       => 'intro',
                    'backgroundColor' => $this->get_background_colors()?->inner?->value,
                    'colorTheme'      => $this->colorTheme->value
                ],
                $beforeComponents
            )];
        }
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
            'beforeComponents' => $this->beforeComponents,
            'panels'           => $this->get_panels(),
            'icon'             => "$this->iconPrefix $this->icon",
        ])->render();
    }
}
