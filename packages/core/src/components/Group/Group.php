<?php
namespace Doubleedesign\Comet\Core;

/**
 * Group component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Group components together for layout or structure purposes.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE, Tag::UL, Tag::OL, Tag::FIGURE, Tag::HEADER, Tag::FOOTER])]
#[DefaultTag(Tag::DIV)]
class Group extends UIComponent {
    use ColorPair;
    use NestedState;

    /**
     * @var ?array<string> $classes
     * @supported-values group, group--breakout
     */
    protected ?array $classes;

    /**
     * @var array<string,string> $dataAttrs
     * @description Allow for data-* attributes to be passed down to the group explicitly;
     *              useful for cases that aren't worth creating a trait or shared property for (e.g., Columns uses this for data-count)
     */
    protected array $dataAttrs;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Group.group');
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->set_color_pair($attributes);
        if ($this->get_background_color() !== null) {
            $this->simplify_all_background_colors();
        }

        // If this component is nested, it stands to reason that its inner components should also be treated as nested,
        // so let's propagate that state down to them automatically
        if ($this->get_is_nested()) {
            array_walk($this->innerComponents, function(&$component) {
                if (method_exists($component, 'set_is_nested')) {
                    $component->set_is_nested(true);
                }
            });
        }

        $this->dataAttrs = array_filter($attributes, fn($key) => str_starts_with($key, 'data-'), ARRAY_FILTER_USE_KEY);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }
        if (isset($this->backgroundColor)) {
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
