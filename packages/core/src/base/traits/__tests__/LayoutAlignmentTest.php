<?php

use PHPUnit\Framework\{Attributes\Test};
use Doubleedesign\Comet\Core\{LayoutAlignment, Alignment};
use function Phluent\Expect;

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  array  $attributes
 *
 * @return object
 */
function create_component_With_layout_alignment(array $attributes): object {
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
    $component = create_component_With_layout_alignment(['justifyContent' => 'center']);

    Expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valid horizontal value from wp layout', function() {
    $component = create_component_With_layout_alignment(['layout' => ['justifyContent' => 'center']]);

    Expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value', function() {
    $component = create_component_With_layout_alignment(['alignItems' => 'center']);

    Expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value from wp layout', function() {
    $component = create_component_With_layout_alignment(['layout' => ['alignItems' => 'center']]);

    Expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value from wp', function() {
    $component = create_component_With_layout_alignment(['verticalAlignment' => 'center']);

    Expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets null horizontal value', function() {
    $component = create_component_With_layout_alignment(['hAlign' => 'invalid']);

    Expect($component->get_hAlign())->toBeNull();
});

test('sets null vertical value', function() {
    $component = create_component_With_layout_alignment(['vAlign' => 'invalid']);

    Expect($component->get_vAlign())->toBeNull();
});
