<?php
namespace Doubleedesign\Comet\Core;

/**
 * PageSection component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description A basic full-width section to separate content areas, with optional background color or gradient.
 *              Intended for automatic use as the wrapper for Container components
 *              and as the parent for components that are not intended to be nested, such as those extending WrappedLayoutComponent.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::FIGURE])]
#[DefaultTag(Tag::SECTION)]
class PageSection extends UIComponent {
    use BackgroundColor;
    use ColorTheme;
    use LayoutContainerSize;

    // This should only ever have a single background colour,
    // which has probably been pre-validated by the parent component using the BackgroundColor trait
    private ThemeColor|ThemeGradient|null $backgroundColor = null;

    /**
     * @var array<Renderable> $innerComponents
     * @description Inner components to be rendered within this component
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.PageSection.page-section');
        $this->set_shortname($attributes['shortName'] ?? 'page-section');
        $this->set_size($attributes);
        $this->set_background_colors($attributes);
        $this->set_color_theme($attributes);
        $this->innerComponents = $innerComponents;
    }

    public function get_filtered_classes(): array {
        return array_unique(array_merge(
            parent::get_filtered_classes(),
            [$this->get_shortname()]
        ));
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->size)) {
            $attributes['data-size'] = $this->size->value;
        }

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->get_background_colors()->outer->value;
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
