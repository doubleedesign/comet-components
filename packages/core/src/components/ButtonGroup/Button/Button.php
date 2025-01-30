<?php
namespace Doubleedesign\Comet\Core;

class Button extends TextElement {
	use HasAllowedTags;

	/**
	 * @var ?Tag
	 * @description The HTML tag to use for the component
	 */
	protected ?Tag $tagName = Tag::A;

	/**
	 * Specify default allowed Tags using the HasAllowedTags trait
	 * (Many child classes will override this with specific tags)
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::A, Tag::BUTTON];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Button.button');
		$this->tagName = isset($attributes['tagName']) ? Tag::tryFrom($attributes['tagName']) : Tag::A;
	}

	function render(): void {

	}
}
