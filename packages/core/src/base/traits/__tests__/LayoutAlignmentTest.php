<?php

use Doubleedesign\Comet\Core\{Alignment, LayoutAlignment};
use PHPUnit\Framework\Attributes\Test;

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 */
function create_component_With_layout_alignment(array $attributes): object {
    return new class($attributes) {
        use LayoutAlignment;
        private array $rawAttributes = [];

        public function __construct(array $attributes) {
            $this->set_layout_alignment_from_attrs($attributes);
        }

        public function get_hAlign() {
            return $this->hAlign;
        }

        public function get_vAlign() {
            return $this->vAlign;
        }

        // Simulate adding attributes to the component like Renderable does,
        // but keep them in this instance for isolation
        public function add_attributes(array $attributes): void {
            $this->rawAttributes = array_merge($this->rawAttributes, $attributes);
        }
    };
}

test('sets valid horizontal value', function() {
    $component = create_component_With_layout_alignment(['justifyContent' => 'center']);

    expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valid horizontal value from wp layout', function() {
    $component = create_component_With_layout_alignment(['layout' => ['justifyContent' => 'center']]);

    expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value', function() {
    $component = create_component_With_layout_alignment(['alignItems' => 'center']);

    expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value from wp layout', function() {
    $component = create_component_With_layout_alignment(['layout' => ['alignItems' => 'center']]);

    expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value from wp', function() {
    $component = create_component_With_layout_alignment(['verticalAlignment' => 'center']);

    expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets default horizontal value if an invalid string is passed', function() {
    $component = create_component_With_layout_alignment(['hAlign' => 'invalid']);

    expect($component->get_hAlign())->toBe(Alignment::MATCH_PARENT);
});

test('sets default vertical value if an invalid string is passed', function() {
    $component = create_component_With_layout_alignment(['vAlign' => 'invalid']);

    expect($component->get_vAlign())->toBe(Alignment::MATCH_PARENT);
});
