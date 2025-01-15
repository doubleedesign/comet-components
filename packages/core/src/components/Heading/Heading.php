<?php
namespace Doubleedesign\Comet\Core;

class Heading extends TextElement {
	use HasAllowedTags;

	/**
	 * @var Tag|null
	 */
	protected ?Tag $tag = Tag::H2;
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
	protected static function get_allowed_html_tags(): array {
		return [Tag::H1, Tag::H2, Tag::H3, Tag::H4, Tag::H5, Tag::H6];
	}

	function __construct(array $attributes, string $content) {
		if (isset($attributes['level'])) {
			$attributes['level'] = (int)$attributes['level'];
			$attributes['tagName'] = 'h' . $attributes['level'];
		}
		else {
			$attributes['level'] = 2;
			$attributes['tagName'] = 'h2';
		}

		$bladeFile = 'components.Heading.heading';

		parent::__construct($attributes, $content, $bladeFile);
	}
}
