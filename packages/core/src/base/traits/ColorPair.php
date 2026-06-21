<?php

namespace Doubleedesign\Comet\Core;

trait ColorPair {
    use BackgroundColor;
    use ColorTheme;

    public function set_color_pair(array $attributes, float $contrastThreshold = 3): void {
        $colorUtils = new ColorUtils();

        $this->set_background_color($attributes);
        $this->set_color_theme($attributes);

        // FIXME: Should only check against global background if component is not nested,
        // but that could be a problem when the nested state is updated after instantiation (e.g., by PageSection)
        // $backgroundToCheck = $this->backgroundColor ?? ThemeColor::tryFrom(Config::getInstance()->get_global_background());
        $backgroundToCheck = $this->backgroundColor;

        // If one or the other is null, skip (note: this will change when comparing against global background is fixed)
        if ($backgroundToCheck === null || $this->colorTheme === null) {
            return;
        }

        // If the pair is already in the global config as a validated pair, then skip further processing
        $theme_pairs = Config::getInstance()->get_theme_colour_pairs();
        $matched_pair = array_find($theme_pairs, function($pair) use ($backgroundToCheck) {
            return $this->get_color_theme()->value === $pair['foreground'] && $backgroundToCheck->value === $pair['background'];
        });
        if ($matched_pair) {
            return;
        }

        $valid = $colorUtils->validate_pair($this->colorTheme, $backgroundToCheck, $contrastThreshold);
        if (!$valid) {
            $background = $backgroundToCheck;
            $foreground = $this->colorTheme->value;
            $message = "ColorPair trait: Foreground $foreground does not have sufficient contrast to be used with $background->value, or there was a problem parsing one of the values.";
            if (function_exists('dump')) {
               // dump($message);
            }
            else {
                error_log($message);
            }

            // Unset the colour theme and let the default styling for an element with the background colour to take over
            $this->colorTheme = null;
        }
        else if ($this->colorTheme === ThemeColor::WHITE || $this->colorTheme === ThemeColor::BLACK) {
            // If the colour theme is black or white, check if that would be the contrast colour of the background (out of those two options)
            // and if so, unset the colorTheme because we don't need it in the HTML - the CSS for elements with the background colour should take care of it
            $calcForeground = $colorUtils->get_best_foreground_color($backgroundToCheck);
            if ($calcForeground->value === $this->colorTheme->value) {
                $this->colorTheme = null;
            }
        }
    }
}
