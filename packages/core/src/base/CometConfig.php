<?php
namespace Doubleedesign\Comet\Core;

class CometConfig {
	private static ThemeColor $globalBackground = ThemeColor::WHITE;

	public static function set_global_background(string $color): void {
		self::$globalBackground = ThemeColor::tryFrom($color);
	}

	public static function get_global_background(): string {
		return self::$globalBackground->value;
	}
}
