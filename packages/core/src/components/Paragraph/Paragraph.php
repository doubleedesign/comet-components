<?php
namespace Doubleedesign\Comet\Core;

class Paragraph extends TextElement {
	use HasAllowedTags;

	/**
	 * @var Tag|null
	 */
	protected ?Tag $tag = Tag::P;

	protected static function get_allowed_html_tags(): array {
		return [Tag::P];
	}

	function __construct(array $attributes, string $content) {
		$bladeFile = 'components.Paragraph.paragraph';
		$attrs = array_merge($attributes, ['tagName' => 'p']);

		parent::__construct($attrs, $content, $bladeFile);
	}
}
