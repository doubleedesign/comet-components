<?php
namespace Doubleedesign\Comet\Core;

/**
 * Container component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Create a section with semantic meaning that controls the maximum width of its contents.
 */
#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV, Tag::ARTICLE, Tag::FOOTER])]
#[DefaultTag(Tag::SECTION)]
class Container extends LayoutComponent {
    use LayoutContainerSize;
    use LayoutOrientation;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Container.container');
        $this->set_size($attributes);
        $this->set_orientation($attributes);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->orientation) && !$this->orientation->isDefault()) {
            $attributes['data-orientation'] = $this->orientation->value;
        }

        if ($this->tagName === Tag::DIV) {
            $attributes['role'] = 'presentation';
        }

        $explicitDataAttrs = array_filter(parent::get_html_attributes(), fn($key) => str_starts_with($key, 'data-'), ARRAY_FILTER_USE_KEY);

        return array_merge($attributes, $explicitDataAttrs);
    }

}
