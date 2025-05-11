<?php
namespace Doubleedesign\Comet\Core;

/**
 * Heading component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a heading element, optionally with a theme style applied.
 */
#[AllowedTags([Tag::H1, Tag::H2, Tag::H3, Tag::H4, Tag::H5, Tag::H6])]
#[DefaultTag(Tag::H2)]
class Heading extends TextElementExtended {
    /**
     * @var array<string> $classes
     * @description CSS classes
     * @supported-values is-style-accent, is-style-small
     */
    protected ?array $classes = [];

    /**
     * @var int|null $level
     * @description Heading level (1-6). Default is 2. Cannot be used in conjunction with tagName.
     */
    protected ?int $level = 2;

    public function __construct(array $attributes, string $content) {
        $proposedTag = Tag::H2;
        // Convert level to tag format for validation
        if (isset($attributes['level']) && is_numeric($attributes['level'])) {
            $proposedTag = Tag::tryFrom('h' . $attributes['level']) ?? Tag::H2;
        }

        // Set the validated tagName
        $attributes['tagName'] = strtolower($proposedTag->value);

        $bladeFile = 'components.Heading.heading';
        parent::__construct($attributes, $content, $bladeFile);
    }
}
