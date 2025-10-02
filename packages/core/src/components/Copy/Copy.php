<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::DIV)]
class Copy extends WrappedLayoutComponent {
    public function __construct(array $attributes, array $innerComponents) {
        $innerComponents = array(
            new Group([
                'colorTheme' => $attributes['colorTheme'] ?? null,
                'isNested'   => true,
                'context'    => $this->get_shortname()
            ], $innerComponents)
        );

        parent::__construct($attributes, $innerComponents, 'components.Copy.copy', !$this->get_is_nested());
    }
}
