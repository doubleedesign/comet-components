<?php
use Doubleedesign\Comet\Core\{ColorUtils, Config, ThemeColor};

describe('ColorUtils', function() {
    beforeEach(function() {
        Config::getInstance()->set_theme_colours([
            'primary'   => '#4B0082', // indigo
            'secondary' => '#FF69B4', // hot pink
            'accent'    => '#FFD700', // gold
            // deliberately leaving out 'info' to test missing colour handling
            'light'     => '#EEEEEE',
            'dark'      => '#222222',
            'white'     => '#FFFFFF',
            'black'     => '#000000',
        ]);
    });

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

    describe('theme colour name to hex value', function() {
        it('matches a valid theme config value to its colour name', function() {
            $indigo = ColorUtils::get_theme_colour_name_from_value('#4B0082');
            expect($indigo)->toEqual('primary');

            $gold = ColorUtils::get_theme_colour_name_from_value('#FFD700');
            expect($gold)->toEqual('accent');
        });

        it('returns null for an invalid hex value', function() {
            $invalid = ColorUtils::get_theme_colour_name_from_value('#123456');
            expect($invalid)->toBeNull();
        });
    });

    describe('contrasting colour selection', function() {

        it('returns white for a dark background', function() {
            $readable = ColorUtils::get_readable_colour(ThemeColor::DARK);
            expect($readable)->toEqual(ThemeColor::WHITE);
        });

        it('returns black for a white background', function() {
            $readable = ColorUtils::get_readable_colour(ThemeColor::WHITE);
            expect($readable)->toEqual(ThemeColor::BLACK);
        });

        it('returns black for a light background', function() {
            $readable = ColorUtils::get_readable_colour(ThemeColor::LIGHT);
            expect($readable)->toEqual(ThemeColor::BLACK);
        });

        it('returns dark for a light background when preferred', function() {
            $readable = ColorUtils::get_readable_colour(ThemeColor::LIGHT, [ThemeColor::DARK]);
            expect($readable)->toEqual(ThemeColor::DARK);
        });

        it('returns a preferred colour when the contrast is sufficient', function() {
            $readable = ColorUtils::get_readable_colour(ThemeColor::PRIMARY, [ThemeColor::ACCENT]);
            expect($readable)->toEqual(ThemeColor::ACCENT);
        });

        it('returns suitable fallback when a preferred colour has insufficient contrast', function() {
            $readable = ColorUtils::get_readable_colour(ThemeColor::SECONDARY, [ThemeColor::ACCENT]);
            expect($readable)->toEqual(ThemeColor::BLACK);
        });

        it('returns black when the colour is not found and global background is white', function() {
            Config::getInstance()->set_global_background(ThemeColor::WHITE);
            $readable = ColorUtils::get_readable_colour(ThemeColor::INFO);
            expect($readable)->toEqual(ThemeColor::BLACK);
        });

        it('returns white when the colour is not found and global background is dark', function() {
            Config::getInstance()->set_global_background(ThemeColor::DARK);
            $readable = ColorUtils::get_readable_colour(ThemeColor::INFO);

            expect($readable)->toEqual(ThemeColor::WHITE);
        });

        it('returns black when there is no theme palette set', function() {
            Config::getInstance()->set_theme_colours([]);
            Config::getInstance()->set_global_background(ThemeColor::WHITE); // default is white but let's be explicit

            $readable = ColorUtils::get_readable_colour(ThemeColor::PRIMARY);

            expect($readable)->toEqual(ThemeColor::BLACK);
        });
    });
});
