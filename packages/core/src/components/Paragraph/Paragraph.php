<?php
namespace Doubleedesign\Comet\Core;

/**
 * Paragraph component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a paragraph element, optionally with a theme style applied.
 */
#[AllowedTags([Tag::P])]
#[DefaultTag(Tag::P)]
class Paragraph extends TextElementExtended {
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 * @supported-values is-style-lead
	 */
	protected ?array $classes = [];

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Paragraph.paragraph');
	}
}
