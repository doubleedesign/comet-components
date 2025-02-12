<?php
namespace Doubleedesign\Comet\Core;

abstract class TextElementExtended extends TextElement {
	/**
	 * @var ThemeColor|null $textColor
	 */
	protected ?ThemeColor $textColor = null;

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $content, $bladeFile);
		$this->textColor = isset($attributes['textColor']) ? ThemeColor::tryFrom($attributes['textColor']) : null;
	}

	function get_filtered_classes(): array {
		return array_merge(
			parent::get_filtered_classes(),
			$this->textColor ? ["color-{$this->textColor->value}"] : []
		);
	}

}
