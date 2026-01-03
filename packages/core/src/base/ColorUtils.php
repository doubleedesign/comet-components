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

    public static function get_theme_colour_name_from_value(string $hex): ?string {
        $palette = Config::getInstance()->get_theme_colours();
        if (empty($palette)) return null;

        $flipped = array_flip($palette);

        return $flipped[$hex] ?? null;
    }

    /**
     * Get a contrast-appropriate foreground colour for a given background colour, optionally considering preferred colours first.
     *
     * @param  ThemeColor|string  $color  - The theme colour enum value (or matching string) of the colour to get a readable foreground for.
     * @param  array<ThemeColor|string>  $preferred  An array of preferred ThemeColor values to consider first.
     *
     * @return ThemeColor The best matching colour.
     */
    public static function get_readable_colour(ThemeColor|string $color, array $preferred = []): ThemeColor {
        if (is_string($color)) {
            $color = ThemeColor::tryFrom($color) ?? ThemeColor::WHITE;
        }
        // If the given colour is not defined in the theme, fall back to global background or white
        $hex = self::get_theme_value_for_colour_name($color->value);
        if ($hex === null) {
            $hex = self::get_theme_value_for_colour_name(Config::getInstance()->get_global_background()) ?? '#FFFFFF';
        }

        /** @var Color $data */
        $colourObj = colority()->fromHex($hex);

        // First check the given preferred colours in order, and return the first one with sufficient contrast
        if (!empty($preferred)) {
            /** @var array<Color> $preferredValues */
            $preferredValues = array_map(function($colour) {
                if (is_string($colour)) {
                    $colour = ThemeColor::tryFrom($colour) ?? ThemeColor::BLACK;
                }

                return colority()->fromHex(self::get_theme_value_for_colour_name($colour->value));
            }, $preferred);
            $preferred_valid = self::first_sufficient_of_preferred($colourObj, $preferredValues);
            if ($preferred_valid !== null) {
                $preferred_result = self::get_theme_colour_name_from_value($preferred_valid->getValueColor());

                return ThemeColor::tryFrom($preferred_result);
            }
        }

        // If there is no preferred colour with sufficient contrast, get the best possible foreground colour using the fallbacks Colority provides
        $readableHex = $colourObj->getBestForegroundColor()->getValueColor();
        $readableName = self::get_theme_colour_name_from_value($readableHex);

        return ThemeColor::tryFrom($readableName) ?? ThemeColor::BLACK;
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

    /**
     * Find the first colour in the preferred list that has sufficient contrast with the given background.
     *
     * @param  Color  $background  - Hex colour of the background
     * @param  array<Color>  $preferred  - Array of hex colours to check
     * @param  float  $threshold  - Contrast ratio threshold
     *
     * @return Color|null - The first sufficient preferred colour, or null if none found
     */
    protected static function first_sufficient_of_preferred(Color $background, array $preferred, float $threshold = 4.5): ?Color {
        // Use array_find if available (PHP 8.4+)
        if (function_exists('array_find')) {
            return array_find($preferred, fn($foreground) => self::has_sufficient_contrast($background, $foreground, $threshold));
        }

        foreach ($preferred as $foreground) {
            if (self::has_sufficient_contrast($background, $foreground, $threshold)) {
                return $foreground;
            }
        }

        return null;
    }
}
