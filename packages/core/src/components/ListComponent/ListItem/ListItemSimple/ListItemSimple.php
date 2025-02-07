<?php
namespace Doubleedesign\Comet\Core;

class ListItemSimple extends TextElementExtended {
	use HasAllowedTags;

	protected ?Tag $tagName = Tag::LI;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::LI];
	}

	function __construct(array $attributes, string $content) {
		$bladeFile = 'components.ListComponent.ListItem.list-item';
		parent::__construct($attributes, $content, $bladeFile);
	}
}
