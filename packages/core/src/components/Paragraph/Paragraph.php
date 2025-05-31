<?php

namespace Doubleedesign\Comet\Core;

/**
 * Paragraph component
 *
 * @version 1.0.0
 * @description Render a paragraph element, optionally with a theme style applied.
 */
#[AllowedTags([Tag::P])]
#[DefaultTag(Tag::P)]
class Paragraph extends TextElementExtended {
    /**
     * @var array<string>
     * @description CSS classes
     * @supported-values is-style-lead
     */
    protected ?array $classes = [];

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Paragraph.paragraph');
    }
}
