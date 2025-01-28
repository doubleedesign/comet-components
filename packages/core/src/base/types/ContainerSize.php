<?php
namespace Doubleedesign\Comet\Core;

enum ContainerSize: string {
	case WIDE = 'wide';
	case FULLWIDTH = 'fullwidth';
	case NARROW = 'narrow';
	case DEFAULT = 'default';

	public static function fromWordPressClassName(string $value): ?self {
		return match($value) {
			'is-style-wide' => self::WIDE,
			'is-style-fullwidth' => self::FULLWIDTH,
			'is-style-narrow' => self::NARROW,
			default => self::DEFAULT
		};
	}
}
