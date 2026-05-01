<?php
namespace Doubleedesign\Comet\Core;

/**
 * Call-To-Action component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Highlight important information and prompt the user to take action.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE])]
#[DefaultTag(Tag::SECTION)]
class CallToAction extends WrappedLayoutComponent {
    use BackgroundColor;
    use ColorTheme;

    /**
     * @param  array  $attributes
     * @param  array<Heading|Paragraph|ButtonGroup|PreprocessedHTML>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        $this->set_background_colors($attributes);

        $content = new Group([
            'context'         => 'call-to-action',
            'shortName'       => 'content',
            'backgroundColor' => $this->get_background_colors()->inner ?? null,
            'colorTheme'      => $attributes['colorTheme'] ?? null,
        ], $innerComponents);

        unset($attributes['colorTheme']); // don't pass colorTheme to parent, it has already been applied above

        parent::__construct($attributes, [$content], 'components.CallToAction.call-to-action');
    }
}
