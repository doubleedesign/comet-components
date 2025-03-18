<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::TH])]
#[DefaultTag(Tag::TH)]
class TableHeaderCell extends TableCell {
	/**
	 * @var string|null $width
	 * @description Fixed width of the cell, including units
	 */
	protected ?string $width;

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content);
		$this->bladeFile = 'components.Table.TableHeaderCell.table-header-cell';
		$this->width = $attributes['width'] ?? null;
	}

	function get_inline_styles(): array {
		$styles = parent::get_inline_styles();

		if($this->width) {
			$styles['width'] = $this->width;
		}

		return $styles;
	}
}
