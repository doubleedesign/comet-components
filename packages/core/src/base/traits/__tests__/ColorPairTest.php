<?php

use Doubleedesign\Comet\Core\{ColorPair, ColorUtils, Config, ThemeColor};
use function Patchwork\restoreAll;
use function Spies\mock_object_of;

beforeEach(function() {
    Config::init();
    Config::getInstance()->set_global_background(ThemeColor::WHITE);
});

afterEach(function() {
    Config::getInstance()->clear_theme_colour_pairs();
    restoreAll();
});

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  array  $attributes
 *
 * @return object
 */
function create_component_with_color_pair(array $attributes): object {
    return new class($attributes) {
        use ColorPair;

        public function __construct(array $attributes) {
            $this->set_color_pair($attributes);
        }
    };
}

describe('ColorPair trait', function() {

    it('sets both colours if the pair already exists in the global config', function() {
        $colorSpy = mock_object_of(ColorUtils::class);
        $colorSpy->spy_on_method('validate_pair')->will_return(true);
        Config::getInstance()->maybe_add_theme_colour_pairs(array(
            ['white', 'light', 1]
        ));

        $colorSpy->spy_on_method('validate_pair')->will_return(false); // Should be ignored because the pair is in the global config
        $component = create_component_with_color_pair(['colorTheme' => 'white', 'backgroundColor' => 'light']);

        expect($component->get_background_color())->toEqual(ThemeColor::LIGHT)
            ->and($component->get_color_theme())->toEqual(ThemeColor::WHITE);
    });

    it('sets the colorTheme if the background matches the global one and the pair already exists in the global config', function() {
        $colorSpy = mock_object_of(ColorUtils::class);
        $colorSpy->spy_on_method('validate_pair')->will_return(true);
        Config::getInstance()->maybe_add_theme_colour_pairs(array(
            ['light', 'white', 1]
        ));

        $colorSpy->spy_on_method('validate_pair')->will_return(false); // Should be ignored because the pair is in the global config
        $component = create_component_with_color_pair(['colorTheme' => 'light', 'backgroundColor' => 'white']);

        expect($component->get_background_color())->toBeNull()
            ->and($component->get_color_theme())->toEqual(ThemeColor::LIGHT);
    });

    it('sets both colours if they have sufficient contrast', function() {
        $colorSpy = mock_object_of(ColorUtils::class);
        $colorSpy->spy_on_method('validate_pair')->and_return(true);
        $component = create_component_with_color_pair(['colorTheme' => 'primary', 'backgroundColor' => 'light']);

        expect($component->get_background_color())->toEqual(ThemeColor::LIGHT)
            ->and($component->get_color_theme())->toEqual(ThemeColor::PRIMARY);
    });

    it('sets the background but not the colorTheme if it does not have sufficient contrast', function() {
        $colorSpy = mock_object_of(ColorUtils::class);
        $colorSpy->spy_on_method('validate_pair')->and_return(false);
        $component = create_component_with_color_pair(['colorTheme' => 'secondary', 'backgroundColor' => 'light']);

        expect($component->get_background_color())->toEqual(ThemeColor::LIGHT)
            ->and($component->get_color_theme())->toBeNull();
    });

    it('sets the colorTheme if the background is null', function() {
        $component = create_component_with_color_pair(['colorTheme' => 'primary']);

        expect($component->get_background_color())->toBeNull()
            ->and($component->get_color_theme())->toEqual(ThemeColor::PRIMARY);
    });

    it('sets the background if the colorTheme is null (and it does not match the global background', function() {
        $component = create_component_with_color_pair(['backgroundColor' => 'light']);

        expect($component->get_background_color())->toEqual(ThemeColor::LIGHT)
            ->and($component->get_color_theme())->toBeNull();
    });

    it('sets the background if it matches the global background and the component is nested', function() {
        $component = create_component_with_color_pair(['backgroundColor' => 'white', 'isNested' => true]);

        expect($component->get_background_color())->toEqual(ThemeColor::WHITE)
            ->and($component->get_color_theme())->toBeNull();
    });

    describe('ThemeColor is white', function() {
        it('sets the backgroundColor and unsets the colorTheme if the background is a dark colour', function() {
            $colorSpy = mock_object_of(ColorUtils::class);
            $colorSpy->spy_on_method('validate_pair')->and_return(true);
            $colorSpy->spy_on_method('get_best_foreground_color')->and_return(THemeColor::WHITE);

            $component = create_component_with_color_pair(['colorTheme' => 'white', 'backgroundColor' => 'dark']);

            expect($component->get_background_color())->toEqual(ThemeColor::DARK)
                ->and($component->get_color_theme())->toBeNull();
        });

        it('unsets the colour theme if the background is too light', function() {
            $colorSpy = mock_object_of(ColorUtils::class);
            $colorSpy->spy_on_method('validate_pair')->and_return(false);

            $component = create_component_with_color_pair(['colorTheme' => 'white', 'backgroundColor' => 'light']);

            expect($component->get_background_color())->toEqual(ThemeColor::LIGHT)
                ->and($component->get_color_theme())->toBeNull();
        });
    });

    describe('ThemeColor is black', function() {
        it('sets the backgroundColor and unsets the colorTheme if the background is a light colour', function() {
            $colorSpy = mock_object_of(ColorUtils::class);
            $colorSpy->spy_on_method('validate_pair')->and_return(true);
            $colorSpy->spy_on_method('get_best_foreground_color')->and_return(THemeColor::BLACK);

            $component = create_component_with_color_pair(['colorTheme' => 'black', 'backgroundColor' => 'light']);

            expect($component->get_background_color())->toEqual(ThemeColor::LIGHT)
                ->and($component->get_color_theme())->toBeNull();
        });

        it('unsets the colour theme if the background is too dark', function() {
            $colorSpy = mock_object_of(ColorUtils::class);
            $colorSpy->spy_on_method('validate_pair')->and_return(false);

            $component = create_component_with_color_pair(['colorTheme' => 'black', 'backgroundColor' => 'dark']);

            expect($component->get_background_color())->toEqual(ThemeColor::DARK)
                ->and($component->get_color_theme())->toBeNull();
        });
    });

    describe('Background is the same as the global background', function() {
        it('unsets the background colour but sets the colorTheme if it has sufficient contrast', function() {
            $colorSpy = mock_object_of(ColorUtils::class);
            $colorSpy->spy_on_method('validate_pair')->and_return(true);
            $component = create_component_with_color_pair(['colorTheme' => 'primary', 'backgroundColor' => 'white']);

            expect($component->get_background_color())->toBeNull()
                ->and($component->get_color_theme())->toEqual(ThemeColor::PRIMARY);
        });

        it('unsets both values if the colorTheme does not have sufficient contrast', function() {
            $colorSpy = mock_object_of(ColorUtils::class);
            $colorSpy->spy_on_method('validate_pair')->and_return(false);
            $component = create_component_with_color_pair(['colorTheme' => 'light', 'backgroundColor' => 'white']);

            expect($component->get_background_color())->toBeNull()
                ->and($component->get_color_theme())->toBeNull();
        });
    });
});
