<?php
use Doubleedesign\Comet\Core\{Alignment, LayoutAlignment, Config};
use function Patchwork\{redefine, restoreAll};

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

beforeEach(function() {
    Config::init();
});

afterEach(function() {
    restoreAll();
});

test('sets valid horizontal value from attributes', function() {
    $component = create_component_with_layout_alignment(['hAlign' => 'center']);

    expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valid vertical value from attributes', function() {
    $component = create_component_with_layout_alignment(['vAlign' => 'center']);

    expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('sets halign from component defaults if no attribute is provided', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['hAlign' => Alignment::CENTER]);

    $component = create_component_with_layout_alignment([]);

    expect($component->get_hAlign())->toBe(Alignment::CENTER);
});

test('sets valign from component defaults if no attribute is provided', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['vAlign' => Alignment::CENTER]);

    $component = create_component_with_layout_alignment([]);

    expect($component->get_vAlign())->toBe(Alignment::CENTER);
});

test('ignores component defaults if a valid attribute value is provided', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['hAlign' => Alignment::CENTER, 'vAlign' => Alignment::CENTER]);

    $component = create_component_with_layout_alignment(['hAlign' => 'start', 'vAlign' => 'start']);

    expect($component->get_hAlign())->toBe(Alignment::START)
        ->and($component->get_vAlign())->toBe(Alignment::START);
});

test('uses fallback horizontal value when an invalid value is provided', function() {
    $component = create_component_with_layout_alignment(['hAlign' => 'invalid']);

    expect($component->get_hAlign())->toBe(Alignment::MATCH_PARENT);
});

test('uses fallback vertical value  when an invalid value is provided', function() {
    $component = create_component_with_layout_alignment(['vAlign' => 'invalid']);

    expect($component->get_vAlign())->toBe(Alignment::MATCH_PARENT);
});
