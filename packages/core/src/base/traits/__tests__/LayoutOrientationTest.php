<?php
use Doubleedesign\Comet\Core\LayoutOrientation;

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 */
function create_component_with_layout_orientation(array $attributes): object {
    return new class($attributes) {
        use LayoutOrientation;
        private array $rawAttributes = [];

        public function __construct(array $attributes) {
            $this->set_orientation_from_attrs($attributes);
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

describe('LayoutOrientation', function() {

    test('sets valid value to "horizontal" from "orientation" attribute', function() {
        $component = create_component_with_layout_orientation(['orientation' => 'horizontal']);

        expect($component->get_html_attributes()['data-orientation'])->toBe('horizontal');
    });

    test('sets valid value to "vertical" from "orientation" attribute', function() {
        $component = create_component_with_layout_orientation(['orientation' => 'vertical']);

        expect($component->get_html_attributes()['data-orientation'])->toBe('vertical');
    });

    test('sets valid value to "horizontal" from the WordPress-style layout attribute array', function() {
        $component = create_component_with_layout_orientation(['layout' => ['orientation' => 'horizontal']]);

        expect($component->get_html_attributes()['data-orientation'])->toBe('horizontal');
    });

    test('sets valid value to "vertical" from the WordPress-style layout attribute array', function() {
        $component = create_component_with_layout_orientation(['layout' => ['orientation' => 'vertical']]);

        expect($component->get_html_attributes()['data-orientation'])->toBe('vertical');
    });
});
