<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE, Tag::ARTICLE])]
#[DefaultTag(Tag::DIV)]
class CopyBlock extends WrappedLayoutComponent {
    public function __construct(array $attributes, array $innerComponents) {
        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'copy';
        }

        $innerComponents = array(
            new Group([
                'colorTheme' => $attributes['colorTheme'] ?? null,
                'isNested'   => true,
                'context'    => $this->get_shortname()
            ], $innerComponents)
        );

        parent::__construct($attributes, $innerComponents, 'components.CopyBlock.copy', !$this->get_is_nested());
    }
}
