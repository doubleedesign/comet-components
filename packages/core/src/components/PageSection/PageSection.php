<?php
namespace Doubleedesign\Comet\Core;

/**
 * PageSection component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.1.0
 * @description A basic full-width section to group nested content, e.g., a blog post containing an image, copy, author bio, post nav etc.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::FIGURE])]
#[DefaultTag(Tag::SECTION)]
class PageSection extends UIComponent {
    use LayoutContainerSize;

    /**
     * @var array<Renderable> $innerComponents
     * @description Inner components to be rendered within this component
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.PageSection.page-section');
        $this->set_size($attributes);

        array_walk($this->innerComponents, function(&$component) {
            if (method_exists($component, 'set_is_nested')) {
                $component->set_is_nested(true);
            }
        });
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
