<?php

namespace Doubleedesign\Comet\Core;
use Exception;
use Tomloprod\Colority\Colors\Color;
use TypeError;

class ColorUtils {

    public static function get_theme_value_for_colour_name(string $color): ?string {
        $palette = Config::getInstance()->get_theme_colours();
        if (empty($palette)) return null;

        return $palette[$color] ?? null;
    }

    public static function validate_pair(ThemeColor|string $foreground, ThemeColor|string $background, float $threshold = 3): bool {
        try {
            if (is_string($foreground)) {
                $foreground = ThemeColor::tryFrom($foreground);
            }
            if (is_string($background)) {
                $background = ThemeColor::tryFrom($background);
            }

            if ($foreground === null || $background === null) {
                throw new TypeError('Invalid ThemeColor value provided.');
            }
            if (self::get_theme_value_for_colour_name($foreground->value) === null || self::get_theme_value_for_colour_name($background->value) === null) {
                throw new TypeError('ThemeColor value not found in theme configuration.');
            }

            $foregroundHex = self::get_theme_value_for_colour_name($foreground->value);
            $backgroundHex = self::get_theme_value_for_colour_name($background->value);

            if ($foregroundHex === null || $backgroundHex === null) {
                return false;
            }

            $foregroundColor = colority()->fromHex($foregroundHex);
            $backgroundColor = colority()->fromHex($backgroundHex);

            return self::has_sufficient_contrast($backgroundColor, $foregroundColor, $threshold);
        }
        catch (Exception|TypeError $e) {
            error_log($e->getMessage());

            return false;
        }
    }

    /**
     * Check if the contrast between two colours meets the specified threshold.
     *
     * @param  Color  $background
     * @param  Color  $foreground
     * @param  float  $threshold
     *
     * @return bool
     */
    protected static function has_sufficient_contrast(Color $background, Color $foreground, float $threshold = 4.5): bool {
        $contrastRatio = $background->getContrastRatio($foreground);

        return $contrastRatio >= $threshold;
    }
}
