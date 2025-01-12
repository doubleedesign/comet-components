<?php
namespace Doubleedesign\Comet\Components;

class ListItem extends TextElement {
	use HasAllowedTags;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_html_tags(): array {
		return [Tag::LI];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.ListItem.list-item');
	}
}
