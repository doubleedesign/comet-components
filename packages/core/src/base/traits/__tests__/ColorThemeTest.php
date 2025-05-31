<?php

use Doubleedesign\Comet\Core\{ColorTheme, ThemeColor};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
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

test('sets valid value', function() {
    $component = create_component_with_color_theme(['colorTheme' => 'secondary']);

    expect($component->get_color_theme())->toBe(ThemeColor::SECONDARY);
});

test('sets null background color', function() {
    $component = create_component_with_color_theme(['colorTheme' => '#FFF']);

    expect($component->get_color_theme())->toBeNull();
});
