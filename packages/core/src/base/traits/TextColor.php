<?php
namespace Doubleedesign\Comet\Core;

trait TextColor {
	/**
	 * @var ThemeColor|null $textColor
	 */
	protected ?ThemeColor $textColor = null;

	protected function set_text_color_from_attrs(array $attributes): void {
		$this->textColor = isset($attributes['textColor']) ? ThemeColor::tryFrom($attributes['textColor']) : null;
	}
}
