<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::SECTION)]
class Copy extends WrappedLayoutComponent {
    use ColorTheme;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Copy.copy');
        $this->set_color_theme($attributes);

        // For nested instances, default to div tag unless specified otherwise in the attributes
        if ($this->get_is_nested() && !isset($attributes['tagName'])) {
            $this->set_tag('div');
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }
}
