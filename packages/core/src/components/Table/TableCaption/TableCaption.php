<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::CAPTION])]
#[DefaultTag(Tag::CAPTION)]
class TableCaption extends TextElement {
	/**
	 * @var string $position
	 * @description Position of the caption relative to the table
	 * @supported-values top, bottom
	 */
	protected string $position = 'bottom';

	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Table.TableCaption.table-caption');
		$this->set_text_align_from_attrs($attributes);
		$this->context = 'table';
		$this->position = $attributes['position'] ?? $this->position;
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->position)) {
			$attributes['data-position'] = $this->position;
		}

		return $attributes;
	}
}
