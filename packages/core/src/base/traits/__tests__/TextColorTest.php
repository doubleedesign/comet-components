<?php
/** @noinspection PhpUnhandledExceptionInspection */

use Doubleedesign\Comet\Core\{TextColor, ThemeColor};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 */
function create_component_with_text_color(array $attributes): object {
    return new class($attributes) {
        use TextColor;
        private array $rawAttributes = [];

        public function __construct(array $attributes) {
            $this->set_text_color_from_attrs($attributes);
        }

        // Simulate adding attributes to the component like Renderable does,
        // but keep them in this instance for isolation
        public function add_attributes(array $attributes): void {
            $this->rawAttributes = array_merge($this->rawAttributes, $attributes);
        }

        public function get_html_attributes(): array {
            return $this->rawAttributes;
        }
    };
}

describe('TextColor', function() {

    test('sets valid value', function() {
        $component = create_component_with_text_color(['textColor' => 'secondary']);

        expect($component->get_html_attributes()['data-text-color'])->toBe(ThemeColor::SECONDARY->value);
    });

    it('ignores invalid value', function() {
        $component = create_component_with_text_color(['textColor' => 'invalid']);

        expect($component->get_html_attributes())->not->toHaveKey('data-text-color');
    });
});
