<?php
use Doubleedesign\Comet\Core\{ColorTheme, Config, ThemeColor};
use function Patchwork\{redefine, restoreAll};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  array  $attributes
 *
 * @return object
 */
function create_component_with_color_theme(array $attributes): object {
    return new class($attributes) {
        use ColorTheme;

        public function __construct(array $attributes) {
            $this->set_color_theme_from_attrs($attributes);
        }

        public function get_color_theme() {
            return $this->colorTheme;
        }
    };
}

beforeEach(function() {
    Config::init();
});

afterEach(function() {
	restoreAll();
});

test('sets valid value from a ThemeColor', function() {
    $component = create_component_with_color_theme(['colorTheme' => ThemeColor::ACCENT]);

    expect($component->get_color_theme())->toBe(ThemeColor::ACCENT);
});

test('sets valid value from string', function() {
    $component = create_component_with_color_theme(['colorTheme' => 'secondary']);

    expect($component->get_color_theme())->toBe(ThemeColor::SECONDARY);
});

test('it uses a ThemeColor value from component defaults if an attribute is not provided', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['colorTheme' => 'info']);

    $component = create_component_with_color_theme([]);

    expect($component->get_color_theme())->toBe(ThemeColor::INFO);
});

test('it uses a string value from component defaults if an attribute is not provided', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['colorTheme' => ThemeColor::ACCENT]);

    $component = create_component_with_color_theme([]);

    expect($component->get_color_theme())->toBe(ThemeColor::ACCENT);
});

test('sets null background color if provided value is "inherit"', function() {
    $component = create_component_with_color_theme(['colorTheme' => 'inherit']);

    expect($component->get_color_theme())->toBeNull();
});

test('sets null background color if provided value is "inherit" even if there is a component default set', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['colorTheme' => ThemeColor::ACCENT]);
    $component = create_component_with_color_theme(['colorTheme' => 'inherit']);

    expect($component->get_color_theme())->toBeNull();
});

test('sets null background color if an invalid value is provided as an attribute', function() {
    $component = create_component_with_color_theme(['colorTheme' => '#FFF']);

    expect($component->get_color_theme())->toBeNull();
});

test('uses a component default if an invalid value is provided as an attribute', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['colorTheme' => ThemeColor::ACCENT]);
    $component = create_component_with_color_theme(['colorTheme' => '#FFF']);

    expect($component->get_color_theme())->toBe(ThemeColor::ACCENT);
});
