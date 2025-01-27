<?php
namespace Doubleedesign\Comet\Core;

class Column extends LayoutComponent {
	private ?string $width;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Columns.Column.column');
		$this->shortName = 'columns__column';
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
