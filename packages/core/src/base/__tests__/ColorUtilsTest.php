<?php

use Doubleedesign\Comet\Core\{ColorUtils, ThemeColor};
use function Spies\{stub_function, expect_spy, get_spy_for, match_pattern, finish_spying};

beforeEach(function() {
    // Mock a range of colour types
    stub_function('file_get_contents')->when_called->will_return(
        <<<CSS
		:root {
			--color-primary: #4B0082;
			--color-secondary: rgb(30, 75, 222);
			--color-accent: hsl(51, 100%, 50%);
			--color-info: lch(65% 45 240);
			--color-warning: lab(75% 17.50 65);
			--color-dark: oklch(0.25 0 275);
			--color-light: ghostwhite;
			--color-invalidName: #8a1010;
		}
		CSS
    );
});

afterEach(function() {
	finish_spying();
});

describe('ColorUtils', function() {

    describe('parsing values from CSS', function() {
        it('saves valid colours (by name) from the CSS file and ignores invalid ones', function() {
            $instance = new ColorUtils();
            $values = $instance->get_theme_colour_values();

            expect(array_keys($values))->toContain('secondary')
                ->and(array_keys($values))->toContain('accent')
                ->and($values)->not->toContain('invalidName');
        });
    });

    describe('theme value to colour name', function() {
        it('matches a valid colour name to its theme config value (hex)', function() {
            $instance = new ColorUtils();
            $colour = $instance->get_theme_value_for_colour_name('primary');
            expect($colour)->toEqual('#4b0082');
        });

        it('matches a valid colour name to its theme config value (RGB)', function() {
            $instance = new ColorUtils();
            $colour = $instance->get_theme_value_for_colour_name('secondary');
            expect($colour)->toEqual('rgb(30, 75, 222)');
        });

        it('matches a valid colour name to its theme config value (HSL)', function() {
            $instance = new ColorUtils();
            $colour = $instance->get_theme_value_for_colour_name('accent');
            expect($colour)->toEqual('hsl(51, 100%, 50%)');
        });

        it('matches a valid colour name to its theme config value (OKLCH)', function() {
            $instance = new ColorUtils();
            $colour = $instance->get_theme_value_for_colour_name('dark');
            expect($colour)->toEqual('oklch(0.25 0 275)');
        });

        it('matches a valid named colour to its hex value', function() {
            $instance = new ColorUtils();
            $colour = $instance->get_theme_value_for_colour_name('light');
            expect($colour)->toEqual('#f8f8ff');
        });

        it('returns null for an invalid colour name', function() {
            $instance = new ColorUtils();
            $invalid = $instance->get_theme_value_for_colour_name('invalid-color');
            expect($invalid)->toBeNull();
        });
    });

    describe('colour pair validation', function() {
        it('returns true when the contrast is sufficient (pre-set hex colours)', function() {
            $instance = new ColorUtils();
            $isValid = $instance->validate_pair(ThemeColor::WHITE, ThemeColor::BLACK, 3);
            expect($isValid)->toBeTrue();
        });

        it('returns true when the contrast is sufficient (hex + hex)', function() {
            $instance = new ColorUtils();
            $isValid = $instance->validate_pair(ThemeColor::PRIMARY, ThemeColor::WHITE, 3);
            expect($isValid)->toBeTrue();
        });

        it('returns true when the contrast is sufficient (RGB + named colour)', function() {
            $instance = new ColorUtils();
            $isValid = $instance->validate_pair(ThemeColor::SECONDARY, ThemeColor::LIGHT, 3);
            expect($isValid)->toBeTrue();
        });

        it('returns true when the contrast is sufficient (RGB + hex)', function() {
            $instance = new ColorUtils();
            $isValid = $instance->validate_pair(ThemeColor::SECONDARY, ThemeColor::WHITE, 3);
            expect($isValid)->toBeTrue();
        });

        it('returns false when the contrast is insufficient', function() {
            $instance = new ColorUtils();
            $isValid = $instance->validate_pair(ThemeColor::DARK, ThemeColor::BLACK, 3);
            expect($isValid)->toBeFalse();
        });

        it('catches the error and returns false if a colour has a valid name but missing from the config', function() {
            $instance = new ColorUtils();
            $logSpy = get_spy_for('error_log');
            $isValid = $instance->validate_pair(ThemeColor::INFO, ThemeColor::BLACK, 3);

            expect_spy($logSpy)->to_have_been_called->with(match_pattern('/ThemeColor value not found in theme configuration/'))->verify();
            expect($isValid)->toBeFalse();
        });

        it('catches the error and returns false if an invalid colour is provided', function() {
            $instance = new ColorUtils();
            $logSpy = get_spy_for('error_log');
            $isValid = $instance->validate_pair(ThemeColor::WHITE, 'invalid-color', 3);

            expect_spy($logSpy)->to_have_been_called()->with(match_pattern('/Invalid ThemeColor value provided/'))->verify();
            expect($isValid)->toBeFalse();
        });
    });
});
