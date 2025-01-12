<?php
namespace Doubleedesign\Comet\Components;

class Table extends TextElement {
	use HasAllowedTags;

	/**
	 * @var Tag|null
	 */
	protected ?Tag $tag = Tag::TABLE;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_html_tags(): array {
		return [Tag::TABLE];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Table.table');
	}
}
