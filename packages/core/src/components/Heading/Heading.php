<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::H1, Tag::H2, Tag::H3, Tag::H4, Tag::H5, Tag::H6])]
#[DefaultTag(Tag::H2)]
class Heading extends TextElementExtended {
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 * @supported-values is-style-accent, is-style-small
	 */
	protected ?array $classes = [];

	function __construct(array $attributes, string $content) {
		$proposedTag = Tag::H2;
		// Convert level to tag format for validation
		if(isset($attributes['level']) && is_numeric($attributes['level'])) {
			$proposedTag = Tag::tryFrom('h' . $attributes['level']) ?? Tag::H2;
		}

		// Set the validated tagName
		$attributes['tagName'] = strtolower($proposedTag->value);

		$bladeFile = 'components.Heading.heading';
		parent::__construct($attributes, $content, $bladeFile);
	}
}
