<?php
namespace Doubleedesign\Comet\Core;

trait BackgroundColor {
	/**
	 * @var ?ThemeColor $backgroundColor
	 * @description Background colour keyword
	 */
	protected ?ThemeColor $backgroundColor = null;

	protected function set_background_color_from_attrs($attributes): void {
		$this->backgroundColor = isset($attributes['backgroundColor'])
			? ThemeColor::tryFrom($attributes['backgroundColor'])
			: null;
	}
}
