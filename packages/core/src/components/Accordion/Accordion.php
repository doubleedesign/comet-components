<?php
namespace Doubleedesign\Comet\Core;

/**
 * Accordion component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Group content into expandable/collapsible panels.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Accordion extends PanelGroupComponent {
    use Icon;

    /**
     * @var ?string $icon
     * @description Icon class name for the icon to use for the expand/collapse indicator.
     */
    protected ?string $icon;

    /** @var array<AccordionPanel> */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
        $this->set_icon_from_attrs($attributes, 'fa-plus');
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'panels'     => $this->get_panels(),
            'icon'       => "$this->iconPrefix $this->icon"
        ])->render();
    }
}
