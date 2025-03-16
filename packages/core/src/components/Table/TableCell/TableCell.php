<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::TH, Tag::TD])]
#[DefaultTag(Tag::TD)]
class TableCell extends TextElement {
	use BackgroundColor;

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Table.TableCell.table-cell');
		$this->set_text_align_from_attrs($attributes);
		$this->set_background_color_from_attrs($attributes);
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}

		return $attributes;
	}
}
