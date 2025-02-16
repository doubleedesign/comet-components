<?php
namespace Doubleedesign\Comet\Core;

trait TextColor {
	/**
	 * @var ThemeColor|null $textColor
	 */
	protected ?ThemeColor $textColor = null;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	protected function set_text_color_from_attrs(array $attributes): void {
		$this->textColor = isset($attributes['textColor']) ? ThemeColor::tryFrom($attributes['textColor']) : null;
	}
}
