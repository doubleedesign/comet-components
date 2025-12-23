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
    use ColorTheme;

    /**
     * @var ?ThemeColor $innerBackground;
     * @description Background colour of the inner content.
     */
    protected ?ThemeColor $innerBackground;

    /**
     * @param  array  $attributes
     * @param  array<Heading|Paragraph|ButtonGroup>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        $content = new Group([
            'context'         => 'call-to-action',
            'shortName'       => 'content',
            'backgroundColor' => $attributes['innerBackground'] ?? null,
            'colorTheme'      => $attributes['colorTheme'] ?? null,
        ], $innerComponents);

        unset($attributes['colorTheme']);

        parent::__construct($attributes, [$content], 'components.CallToAction.call-to-action');
    }
}
