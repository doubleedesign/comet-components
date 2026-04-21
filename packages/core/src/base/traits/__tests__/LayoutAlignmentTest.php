<?php

use Doubleedesign\Comet\Core\{Alignment, LayoutAlignment};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  array  $attributes
 *
 * @return object
 */
function create_component_with_layout_alignment(array $attributes): object {
    return new class($attributes) {
        use LayoutAlignment;

        public function __construct(array $attributes) {
            $this->set_layout_alignment_from_attrs($attributes);
        }

        public function get_hAlign() {
            return $this->hAlign;
        }

        public function get_vAlign() {
            return $this->vAlign;
        }
    };
}

test('sets valid horizontal value', function() {
    $component = create_component_with_layout_alignment(['hAlign' => 'center']);

    expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value', function() {
    $component = create_component_with_layout_alignment(['vAlign' => 'center']);

    expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('uses fallback horizontal value when an invalid value is provided', function() {
    $component = create_component_with_layout_alignment(['hAlign' => 'invalid']);

    expect($component->get_hAlign())->toBe(Alignment::MATCH_PARENT);
});

test('uses fallback vertical value  when an invalid value is provided', function() {
    $component = create_component_with_layout_alignment(['vAlign' => 'invalid']);

    expect($component->get_vAlign())->toBe(Alignment::MATCH_PARENT);
});
