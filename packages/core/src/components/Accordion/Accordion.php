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
    private bool $hasRenderedWrapper = false;
    private WrappedPanelGroup $wrappedComponent;

    public function __construct(array $attributes, array $innerComponents, ?array $beforeComponents = []) {
        $this->set_icon_from_attrs($attributes, 'fa-plus');
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->beforeComponents = $beforeComponents ?? [];

        // Create inner PanelGroupComponent
        parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');

        // Add intro and wrappers
        $this->wrappedComponent = new WrappedPanelGroup(
            array_merge($attributes, [
                'shortName' => $this->get_shortname() === 'accordion' ? 'accordion-wrapper' : $this->get_shortname(),
            ]),
            $this->beforeComponents,
            $this
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        if (!$this->hasRenderedWrapper) {
            $this->hasRenderedWrapper = true; // updating this before actually rendering prevents infinite loops
            $this->wrappedComponent->render();
        }
        else {
            echo $blade->make($this->bladeFile, [
                'classes'    => $this->get_filtered_classes(),
                'attributes' => $this->get_html_attributes(),
                'panels'     => $this->get_panels(),
                'icon'       => "$this->iconPrefix $this->icon",
            ])->render();
        }
    }
}
