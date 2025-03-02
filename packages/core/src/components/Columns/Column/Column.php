<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Column extends LayoutComponent {
	/**
	 * @var ?string $width
	 * Optional set width of the column. Note: This may be overridden to stack columns on small viewports.
	 */
	private ?string $width;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'columns']),
			$innerComponents,
			'components.Columns.Column.column');
		$this->width = $attributes['width'] ?? null;
	}

	public function get_width() {
		return $this->width;
	}

	public function set_width(string|null $width) {
		$this->width = $width;
	}

	function get_filtered_classes(): array {
		$classes = array_merge([$this->shortName], parent::get_filtered_classes());

		if(isset($this->width)) {
			$classes[] = 'columns__column--has-own-width';
		}

		return $classes;
	}

	function get_inline_styles(): array {
		$styles = parent::get_inline_styles();

		if(isset($this->width)) {
			$styles['width'] = $this->width;
			$styles['flex-basis'] = $this->width;
		}

		return $styles;
	}
}
