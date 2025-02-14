<?php
namespace Doubleedesign\Comet\Core;

trait ColorTheme {
	/**
	 * @var ?ThemeColor $colorTheme
	 * @description Colour keyword for the fill or outline colour
	 */
	protected ?ThemeColor $colorTheme = ThemeColor::PRIMARY;

	protected function set_color_theme_from_attrs($attributes): void {
		$this->colorTheme = isset($attributes['colorTheme'])
			? ThemeColor::tryFrom($attributes['colorTheme'])
			: ThemeColor::PRIMARY;
	}
}
