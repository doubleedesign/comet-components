<?php

use Doubleedesign\Comet\Core\{ColorUtils, ThemeColor};
use function Spies\{expect_spy, get_spy_for, match_pattern};

describe('ColorUtils', function() {

    describe('theme value to colour name', function() {
        it('matches a valid colour name to its theme config value', function() {
            $primary = ColorUtils::get_theme_value_for_colour_name('primary');
            expect($primary)->toEqual('#4B0082');

            $accent = ColorUtils::get_theme_value_for_colour_name('accent');
            expect($accent)->toEqual('#FFD700');
        });

        it('returns null for an invalid colour name', function() {
            $invalid = ColorUtils::get_theme_value_for_colour_name('invalid-color');
            expect($invalid)->toBeNull();
        });
    });

    describe('colour pair validation', function() {
        it('returns true when the contrast is sufficient', function() {
            $isValid = ColorUtils::validate_pair(ThemeColor::WHITE, ThemeColor::BLACK, 3);
            expect($isValid)->toBeTrue();
        });

        it('returns false when the contrast is insufficient', function() {
            $isValid = ColorUtils::validate_pair(ThemeColor::DARK, ThemeColor::BLACK, 3);
            expect($isValid)->toBeFalse();
        });

        it('catches the error and returns false if a colour has a valid name but missing from the config', function() {
            $logSpy = get_spy_for('error_log');
            $isValid = ColorUtils::validate_pair(ThemeColor::INFO, ThemeColor::BLACK, 3);

            expect_spy($logSpy)->to_have_been_called->with(match_pattern('/ThemeColor value not found in theme configuration/'))->verify();
            expect($isValid)->toBeFalse();
        });

        it('catches the error and returns false if an invalid colour is provided', function() {
            $logSpy = get_spy_for('error_log');
            $isValid = ColorUtils::validate_pair(ThemeColor::WHITE, 'invalid-color', 3);

            expect_spy($logSpy)->to_have_been_called()->with(match_pattern('/Invalid ThemeColor value provided/'))->verify();
            expect($isValid)->toBeFalse();
        });
    });
});
