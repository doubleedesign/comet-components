<?php
namespace Doubleedesign\Comet\Core;


class Heading extends TextElementExtended {
	use HasAllowedTags;

	/**
	 * @var Tag|null
	 */
	protected ?Tag $tagName = Tag::H2;
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 * @supported-values is-style-accent, is-style-small
	 */
	protected ?array $classes = [];

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::H1, Tag::H2, Tag::H3, Tag::H4, Tag::H5, Tag::H6];
	}

	function __construct(array $attributes, string $content) {
		$proposedTag = Tag::H2;
		// Convert level to tag format for validation
		if (isset($attributes['level']) && is_numeric($attributes['level'])) {
			$proposedTag = Tag::tryFrom('h' . $attributes['level']) ?? Tag::H2;
		}

		// Validate the tag against allowed tags and fall back to h2 if invalid
		if (!$this->validate_html_tag($proposedTag)) {
			$proposedTag = Tag::H2;
		}

		// Set the validated tagName
		$attributes['tagName'] = strtolower($proposedTag->value);

		$bladeFile = 'components.Heading.heading';
		parent::__construct($attributes, $content, $bladeFile);
	}
}
