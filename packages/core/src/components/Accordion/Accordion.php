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

    /**
     * @var array<Renderable> $beforeComponents
     * @description Components to render before the accordion (e.g. heading, intro text).
     */
    protected array $beforeComponents = [];

    public function __construct(array $attributes, array $innerComponents, ?array $beforeComponents = []) {
        $this->set_icon_from_attrs($attributes, 'fa-plus');
        $this->isNested = $attributes['isNested'] ?? $this->isNested;
        $this->beforeComponents = $beforeComponents ?? $this->beforeComponents;
        parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
    }

    public function get_container_attributes(): array {
        return [
            'withWrapper'     => true,
            'classes'         => [$this->context ? "{$this->context}__{$this->shortName}-wrapper" : "{$this->shortName}-wrapper"],
            'size'            => $this->size ?? null
        ];
    }

    public function get_intro_attributes(): array {
        $attrs = [
            'class' => $this->context ? "{$this->context}__{$this->shortName}-intro" : "{$this->shortName}-intro"
        ];

        if ($this->colorTheme) {
            $attrs['data-color-theme'] = $this->colorTheme->value;
        }
        if ($this->backgroundColor) {
            $attrs['data-background'] = $this->backgroundColor->value;
        }

        return $attrs;
    }

    protected function render_standalone(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'introAttributes'  => $this->get_intro_attributes(),
            'beforeComponents' => $this->beforeComponents,
            'classes'          => $this->get_filtered_classes(),
            'attributes'       => $this->get_html_attributes(),
            'panels'           => $this->get_panels(),
            'icon'             => "$this->iconPrefix $this->icon"
        ])->render();
    }

    protected function render_with_wrapper(): void {
        $inner = $this;
        $inner->isNested = true; // Prevent infinite loop

        $withWrapper = new Container($this->get_container_attributes(), [$inner]);
        $withWrapper->render();
    }

    public function render(): void {
        if (!$this->isNested) {
            $this->render_with_wrapper();
        }
        else {
            $this->render_standalone();
        }
    }
}
