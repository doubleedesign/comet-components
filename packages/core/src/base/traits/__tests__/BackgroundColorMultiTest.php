<?php

use Doubleedesign\Comet\Core\{BackgroundCollection, Config, BackgroundColorMulti, ThemeColor, ThemeGradient};
use function Patchwork\{redefine, relay, restoreAll};
use function Spies\{make_spy, match_array, expect_spy};

beforeEach(function() {
    Config::init();
    Config::getInstance()->set_global_background(ThemeColor::WHITE);
});

afterEach(function() {
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
function create_component_with_bg_color(array $attributes): object {
    return new class($attributes) {
        use BackgroundColorMulti;

        public function __construct(array $attributes) {
            $this->set_background_colors($attributes);
        }
    };
}

// Ensure backwards compatibility with single-colour attribute name
dataset('all valid attribute names', [['backgroundColor'], ['backgroundColors']]);

dataset('all valid attribute types (single colour)', [
    'ThemeColor object'                 => ThemeColor::PRIMARY,
    'ThemeColor string'                 => 'primary',
    'Array with single object'          => [ThemeColor::PRIMARY],
    'Array with single string'          => ['primary'],
]);

dataset('all valid attribute types (single gradient)', [
    'ThemeGradient object'              => new ThemeGradient('dark', 'light'),
    'ThemeGradient string'              => 'dark-light',
    'Array with single object'          => [new ThemeGradient('dark', 'light')],
    'Array with single string'          => ['dark-light'],
]);

describe('Set value from attributes', function() {
    it('sets a valid single colour', function(string $attributeName, $value) {
        $component = create_component_with_bg_color([$attributeName => $value]);

        expect($component->get_background_color())->toEqual(ThemeColor::PRIMARY)
            ->and($component->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY);
    })->with('all valid attribute names')
        ->with('all valid attribute types (single colour)');

    it('sets a valid colour pair', function() {
        $spy = make_spy();
        redefine('Doubleedesign\Comet\Core\BackgroundCollection::transform_to_collection', function($value) use ($spy) {
            $spy($value);

            return relay();
        });

        $component = create_component_with_bg_color(['backgroundColors' => ['light', 'dark']]);

        expect_spy($spy)->to_have_been_called->with(match_array(['light', 'dark']))->verify();
        expect($component->get_background_colors())->toBeInstanceOf(BackgroundCollection::class);
    })->with('all valid attribute names');
});

describe('Set value from component defaults', function() {
    it('sets a valid single colour', function(string $attributeName, $value) {
        redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => [$attributeName => $value]);

        $component = create_component_with_bg_color([]);

        expect($component->get_background_color())->toEqual(ThemeColor::PRIMARY);
        expect($component->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY);
    })->with('all valid attribute names')
        ->with('all valid attribute types (single colour)');

    it('sets a valid colour pair', function(string $attributeName) {
        redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['backgroundColors' => ['light', 'dark']]);
        $spy = make_spy();
        redefine('Doubleedesign\Comet\Core\BackgroundCollection::transform_to_collection', function($value) use ($spy) {
            $spy($value);

            return relay();
        });

        $component = create_component_with_bg_color(['backgroundColors' => ['light', 'dark']]);

        expect_spy($spy)->to_have_been_called->with(match_array(['light', 'dark']))->verify();
        expect($component->get_background_colors())->toBeInstanceOf(BackgroundCollection::class);
    })->with('all valid attribute names');
});
