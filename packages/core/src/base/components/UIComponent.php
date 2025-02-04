<?php
namespace Doubleedesign\Comet\Core;
use InvalidArgumentException;

abstract class UIComponent extends Renderable {
	use HasAllowedTags;

	/**
	 * @var array<Renderable> $innerComponents
	 * @description Inner components to be rendered within this component
	 */
	protected array $innerComponents;

	/**
	 * @var ?Tag $tagName
	 * @description The HTML tag to use for this component
	 */
	protected ?Tag $tagName = Tag::DIV;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE];
	}

	/**
	 * UIComponent constructor
	 * @param array<string, string|int|array|null> $attributes
	 * @param array<Renderable> $innerComponents
	 * @param string $bladeFile
	 */
	function __construct(array $attributes, array $innerComponents, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->innerComponents = $innerComponents;

		// If tagName is set, validate it before setting the property
		try {
			if (isset($attributes['tagName'])) {
				$tag = Tag::tryFrom($attributes['tagName']);
				if ($tag && $this->validate_html_tag($tag)) {
					$this->tagName = $tag;
				}
				else {
					throw new InvalidArgumentException(
						"Tag $tag->value is not allowed for " . get_class($this) .
						". Allowed tags are: " . implode(', ', array_map(fn($tag) => $tag->value, static::get_allowed_wrapping_tags()))
					);
				}
			}
		}
		catch (InvalidArgumentException $e) {
			error_log(print_r($e->getMessage(), true));
			// Default to div if the tag was invalid
			$this->tagName = Tag::DIV;
		}
	}

	/**
	 * Get the filtered class list for this component as a string
	 * @return string
	 */
	protected function get_filtered_classes_string(): string {
		return implode(' ', $this->get_filtered_classes());
	}

}
