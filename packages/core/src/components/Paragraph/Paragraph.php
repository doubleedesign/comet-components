<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::P])]
#[DefaultTag(Tag::P)]
class Paragraph extends TextElementExtended {

	function __construct(array $attributes, string $content) {
		$bladeFile = 'components.Paragraph.paragraph';
		$attrs = array_merge($attributes, ['tagName' => 'p']);

		parent::__construct($attrs, $content, $bladeFile);
	}
}
