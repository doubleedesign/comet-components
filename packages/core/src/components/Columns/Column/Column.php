<?php
namespace Doubleedesign\Comet\Core;

class Column extends LayoutComponent {
	use HasAllowedTags;

	/**
	 * @var ?string $width
	 * Optional set width of the column. Note: This may be overridden to stack columns on small viewports.
	 */
	private ?string $width;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV, Tag::SECTION, Tag::MAIN, Tag::ARTICLE, Tag::ASIDE];
	}


	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'columns']),
			$innerComponents,
			'components.Columns.Column.column');
		$this->width = $attributes['width'] ?? null;
	}

	function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->width)) {
			$classes[] = 'columns__column--has-own-width';
		}

		return $classes;
	}

	function get_inline_styles(): array {
		$styles = parent::get_inline_styles();

		if (isset($this->width)) {
			$styles['width'] = $this->width;
			$styles['flex-basis'] = $this->width;
		}

		return $styles;
	}
}
