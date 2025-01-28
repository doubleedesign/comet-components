<?php
namespace Doubleedesign\Comet\Core;

class Details extends TextElement {
	use HasAllowedTags;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DETAILS];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Details.details');
	}
}
