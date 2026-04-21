<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
abstract class PanelGroupComponent extends UIComponent {
    use BackgroundColor;
    use ColorTheme;
    use LayoutContainerSize;
    use LayoutOrientation;
    use NestedState;

    /**
     * @var array<PanelComponent> $panels
     * @description Panel data transformed for use by the relevant Vue component.
     */
    private array $panels = [];

    public function __construct(array $attributes, array $innerComponents, string $bladeFile) {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->prepare_inner_components_for_vue($innerComponents);
        $this->set_color_theme($attributes, ThemeColor::PRIMARY);
        $this->set_background_color($attributes);
        $this->set_orientation($attributes);
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->set_size($attributes);
    }

    private function prepare_inner_components_for_vue($rawInnerComponents): void {
        foreach ($rawInnerComponents as $panel) {
            if (!$panel instanceof PanelComponent) {
                error_log('PanelGroupComponent: Invalid inner component type found and ignored.');
            }

            $this->panels[] = [
                'summary' => $panel->get_summary(),
                'content' => $panel->get_content(),
            ];
        }
    }

    protected function get_panels(): array {
        return $this->panels;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->orientation)) {
            $attributes['data-orientation'] = $this->orientation->value;
        }

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }

}
