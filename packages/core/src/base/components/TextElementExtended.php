<?php
namespace Doubleedesign\Comet\Core;

abstract class TextElementExtended extends TextElement {
	use TextColor;

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $content, $bladeFile);
		$this->set_text_color_from_attrs($attributes);
	}

	function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->textColor)) {
			$attributes['data-text-color'] = $this->textColor->value;
		}

		return $attributes;
	}

}
