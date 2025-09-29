<?php
namespace Doubleedesign\Comet\Core;

/**
 * PageSection component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Create a full-width section to separate content areas, with optional background color or gradient.
 *              Intended for automatic use as the wrapper for Container components.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN])]
#[DefaultTag(Tag::SECTION)]
class PageSection extends UIComponent {

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.PageSection.page-section');
        // PageSection should only ever be a top-level component (BEM block) it should never be nested (BEM element)
        $this->set_bem_element(null);
    }

    public function get_filtered_classes(): array {
        return array_merge(
            parent::get_filtered_classes(),
            ['page-section']
        );
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }
        else if (isset($this->gradient)) {
            $attributes['data-background'] = 'gradient-' . $this->gradient;
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
