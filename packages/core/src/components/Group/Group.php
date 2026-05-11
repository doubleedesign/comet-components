<?php
namespace Doubleedesign\Comet\Core;

/**
 * Group component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Group components together for layout or structure purposes.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE, Tag::UL, Tag::OL, Tag::FIGURE])]
#[DefaultTag(Tag::DIV)]
class Group extends UIComponent {
    use BackgroundColor;
    use ColorTheme;
    use NestedState;

    /**
     * @var ?array<string> $classes
     * @supported-values group, group--breakout
     */
    protected ?array $classes;

    /**
     * @var array $dataAttrs
     * @description Allow for data-* attributes to be passed down to the group explicitly;
     *              useful for cases that aren't worth creating a trait or shared property for (e.g., Columns uses this for data-count)
     */
    protected array $dataAttrs;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Group.group');
        $this->set_color_theme($attributes);
        /* Allow groups without a specified background to be transparent rather than defaulting to the fallback */
        if (isset($attributes['backgroundColor']) || isset($attributes['backgroundColors'])) {
            $this->set_background_color($attributes);
            $this->simplify_all_background_colors();
        }

        $this->dataAttrs = array_filter($attributes, fn($key) => str_starts_with($key, 'data-'), ARRAY_FILTER_USE_KEY);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }
        if (isset($this->backgroundColors)) {
            $attributes['data-background'] = $this->get_background_color()->value;
        }
        else if (isset($this->gradient)) {
            $attributes['data-background'] = 'gradient-' . $this->gradient;
        }

        return array_merge($attributes, $this->dataAttrs);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
