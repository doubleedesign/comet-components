<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\LayoutContainerSize;

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 */
function create_component_with_container_size(array $attributes): object {
    return new class($attributes) {
        use LayoutContainerSize;
        private array $rawAttributes = [];

        public function __construct(array $attributes) {
            $this->set_size_from_attrs($attributes);
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

describe('LayoutContainerSize', function() {

    test('sets valid value from "size" attribute', function() {
        $component = create_component_with_container_size(['size' => 'narrow']);

        expect($component->get_html_attributes()['data-size'])->toBe('narrow');
    });

    test('sets valid value from WordPress-style className', function() {
        $component = create_component_with_container_size(['className' => 'is-style-wide']);

        expect($component->get_html_attributes()['data-size'])->toBe('wide');
    });
});
