<?php
namespace Doubleedesign\Comet\Core;

class ListItemSimple extends TextElement {
	use HasAllowedTags;

	protected ?Tag $tag = Tag::LI;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::LI];
	}

	function __construct(array $attributes, string $content) {
		$bladeFile = 'components.ListItem.list-item';
		parent::__construct($attributes, $content, $bladeFile);
	}
}
