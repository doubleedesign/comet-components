<?php
namespace Doubleedesign\Comet\Core;

/**
 * Container component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.1.0
 * @description A basic page section to group nested content, e.g., a blog post containing an image, copy, author bio, post nav etc.
 *              Should generally have a shortName specified so the purpose of the grouping is clear.
 */
#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV, Tag::ARTICLE, Tag::FOOTER])]
#[DefaultTag(Tag::SECTION)]
class Container extends UIComponent {
    use LayoutContainerSize;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Container.container');
        $this->set_size($attributes);

        array_walk($this->innerComponents, function(&$component) {
            if (method_exists($component, 'set_is_nested')) {
                $component->set_is_nested(true);
            }
        });
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (!empty($this->size)) {
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
