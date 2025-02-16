<?php
namespace Doubleedesign\Comet\Core;

trait BackgroundColor {
	/**
	 * @var ?ThemeColor $backgroundColor
	 * @description Background colour keyword
	 */
	protected ?ThemeColor $backgroundColor = null;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	protected function set_background_color_from_attrs(array $attributes): void {
		$this->backgroundColor = isset($attributes['backgroundColor'])
			? ThemeColor::tryFrom($attributes['backgroundColor'])
			: null;
	}
}
