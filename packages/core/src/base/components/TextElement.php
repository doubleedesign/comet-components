<?php
namespace Doubleedesign\Comet\Core;
use InvalidArgumentException;

abstract class TextElement extends Renderable {
	use HasAllowedTags;

	/**
	 * @var string $content
	 * @description plain text or basic HTML
	 */
	protected string $content;
	/**
	 * @var Alignment|null $textAlign
	 */
	protected ?Alignment $textAlign = Alignment::MATCH_PARENT;
	/**
	 * @var string|null $textColor
	 */
	protected ?string $textColor = null;

	/**
	 * Specify default allowed Tags using the HasAllowedTags trait
	 * (Many child classes will override this with specific tags)
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return Settings::INLINE_PHRASING_ELEMENTS;
	}

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->content = $content;
		$this->textAlign = isset($attributes['textAlign']) ? Alignment::tryFrom($attributes['textAlign']) : null;
		$this->textColor = isset($attributes['textColor']) ?? null;
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
	 * Collect the inline styles using the relevant supported attributes
	 *
	 * @return array<string, string>
	 */
	function get_inline_styles(): array {
		$styles = [];

		if ($this->textAlign) {
			$styles['text-align'] = $this->textAlign->value;
		}

		return $styles;
	}

	/**
	 * Get the valid/supported classes for this component
	 * @return array<string>
	 */
	function get_filtered_classes(): array {
		$current_classes = parent::get_filtered_classes();

		if ($this->textColor) {
			$current_classes[] = 'text-' . $this->textColor;
		}

		return $current_classes;
	}


	/**
	 * Default render method (child classes may override this)
	 * @return void
	 */
	public function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = implode(' ', $this->get_filtered_classes());

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => $classes,
			'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();

	}
}
