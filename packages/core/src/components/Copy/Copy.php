<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::DIV)]
class Copy extends WrappedLayoutComponent {
    use ColorTheme;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Copy.copy');
        $this->set_color_theme_from_attrs($attributes);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }
}
