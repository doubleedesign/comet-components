<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::SECTION)]
class Copy extends UIComponent {
    use ColorTheme;
    use LayoutContainerSize;
    use NestedState;

    /**
     * @var array<PreprocessedHTML|Heading|ButtonGroup|ImageComponent> $innerComponents
     * @description Inner column components
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Copy.copy');
        $this->set_color_theme($attributes);
        $this->set_is_nested($attributes['isNested'] ?? null);
        $this->set_size($attributes);

        if ($this->get_is_nested() && !isset($attributes['tagName'])) {
            $this->tagName = Tag::DIV;
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
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
