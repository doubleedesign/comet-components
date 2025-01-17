<?php
namespace Doubleedesign\Comet\Core;
use Exception;

abstract class LayoutComponent extends UIComponent {
	use HasAllowedTags;

	protected ?Alignment $hAlign = Alignment::START;
	protected ?Alignment $vAlign = Alignment::START;
	protected ?Tag $tag = Tag::DIV;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV, Tag::SECTION, Tag::HEADER, Tag::FOOTER, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE];
	}

	function __construct(array $attributes, array $children, string $bladeFile) {
		parent::__construct($attributes, $children, $bladeFile);
		$this->hAlign = isset($attrs['justifyContent']) ? Alignment::fromString($attrs['justifyContent']) : Alignment::START;
		$this->vAlign = isset($attrs['verticalAlignment']) ? Alignment::fromString($attrs['verticalAlignment']) : Alignment::START;
		$this->tag = isset($attrs['tagName']) ? Tag::tryFrom($attrs['tagName']) : Tag::DIV;
	}


	/**
	 * Build the inline styles (style attribute) as a single string
	 * using the relevant supported attributes
	 * @return array<string, string>
	 */
	function get_inline_styles(): array {
		return [];
	}

	/**
	 * Default render method (child classes may override this)
	 *
	 * @return void
	 */
	public function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = $attrs['className'] ?? '';

		try {
			echo $blade->make($this->bladeFile, [
				'tag'        => $this->tag->value,
				'classes'    => sprintf('%s %s', $this->shortName, $classes),
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->innerComponents
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e, true));
		}
	}
}
