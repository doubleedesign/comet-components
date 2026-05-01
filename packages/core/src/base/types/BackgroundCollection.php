<?php
namespace Doubleedesign\Comet\Core;

class BackgroundCollection {
    public private(set) ThemeColor|ThemeGradient|null $outer = null;
    public private(set) ?ThemeColor $inner = null;

    public function __construct($outer = null, $inner = null) {
        $this->outer = self::validate_outer($outer);
        $this->inner = self::validate_inner($inner);
    }

    private static function validate_outer($color): ThemeColor|ThemeGradient|null {
		if($color === null) {
			return null;
		}
        if ($color instanceof ThemeColor || $color instanceof ThemeGradient) {
            return $color;
        }

        return ThemeColor::tryFrom($color) ?? ThemeGradient::tryFrom($color) ?? null;
    }

    private static function validate_inner($color): ?ThemeColor {
		if($color === null) {
			return null;
		}
        if ($color instanceof ThemeColor) {
            return $color;
        }

        return ThemeColor::tryFrom($color) ?? null;
    }

    public static function transform_to_collection($value): ?BackgroundCollection {
        if ($value instanceof BackgroundCollection) {
            return $value;
        }
        // Backwards compatibility - handle single values
        else if ($value instanceof ThemeColor || $value instanceof ThemeGradient || is_string($value)) {
            return new BackgroundCollection($value);
        }
        // Also allow arrays, for simplicity in some implementations
        else if (is_array($value)) {
            $outer = $value['outer'] ?? $value[0] ?? null;
            $inner = $value['inner'] ?? $value[1] ?? null;

            $outer = self::validate_outer($outer);
            $inner = self::validate_inner($inner);

            if ($outer === null && $inner === null) {
                return null;
            }

            return new BackgroundCollection($outer, $inner);
        }

        return null;
    }
}
