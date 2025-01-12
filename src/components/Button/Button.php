<?php
namespace Doubleedesign\Comet\Components;

class Button extends TextElement {
	use HasAllowedTags;

	/**
	 * @var Tag|null
	 */
	protected ?Tag $tag = Tag::BUTTON;
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 * @supported-values is-style-hollow
	 */
	protected ?array $classes = []; // TODO: Handle colours

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_html_tags(): array {
		return [Tag::BUTTON, Tag::A, Tag::INPUT];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Button.button');
	}
}
