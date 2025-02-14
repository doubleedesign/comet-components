<?php
namespace Doubleedesign\Comet\Core;

abstract class TextElementExtended extends TextElement {
	use TextColor;

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $content, $bladeFile);
		$this->set_text_color_from_attrs($attributes);
	}

	function get_filtered_classes(): array {
		return array_merge(
			parent::get_filtered_classes(),
			$this->textColor ? ["color-{$this->textColor->value}"] : []
		);
	}

}
