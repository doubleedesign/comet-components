<?php
use Doubleedesign\Comet\Core\{Alignment, TextAlign};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 */
function create_component_with_text_align(array $attributes): object {
    return new class($attributes) {
        use TextAlign;
        private array $rawAttributes = [];

        public function __construct(array $attributes) {
            $this->set_text_align_from_attrs($attributes);
        }

        // Simulate adding attributes to the component like Renderable does,
        // but keep them in this instance for isolation
        public function add_attributes(array $attributes): void {
            $this->rawAttributes = array_merge($this->rawAttributes, $attributes);
            $this->set_text_align_from_attrs($this->rawAttributes);
        }

        /**
         * Get the text alignment value from the protected property
         */
        public function get_text_align() {
            return $this->textAlign;
        }
    };
}

describe('TextAlign', function() {

    test('sets valid value from "textAlign" attribute', function() {
        $component = create_component_with_text_align(['textAlign' => 'center']);

        expect($component->get_text_align())->toBe(Alignment::CENTER);
    });

    test('translates and sets valid value from "align" attribute', function() {
        $component = create_component_with_text_align(['align' => 'right']);

        expect($component->get_text_align())->toBe(Alignment::END);
    });

    test('sets null value', function() {
        $component = create_component_with_text_align(['textAlign' => null]);

        expect($component->get_text_align())->toBeNull();
    });

    test('sets invalid value to the default', function() {
        $component = create_component_with_text_align(['textAlign' => 'invalid']);

        expect($component->get_text_align())->toBe(Alignment::MATCH_PARENT);
    });

    test('it identifies "start" as the default alignment', function() {
        $component = create_component_with_text_align(['textAlign' => 'start']);

        expect($component->get_text_align()->isDefault())->toBeTrue();
    });

    test('it identifies "match-parent" as the default alignment', function() {
        $component = create_component_with_text_align(['textAlign' => 'match-parent']);

        expect($component->get_text_align()->isDefault())->toBeTrue();
    });

    test('it identifies non-default alignment', function() {
        $component = create_component_with_text_align(['textAlign' => 'center']);

        expect($component->get_text_align()->isDefault())->toBeFalse();
    });
});
