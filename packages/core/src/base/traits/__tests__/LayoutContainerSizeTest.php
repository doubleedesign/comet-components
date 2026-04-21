<?php

use Doubleedesign\Comet\Core\{Config, ContainerSize, LayoutContainerSize};
use function Patchwork\{redefine, restoreAll};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  array  $attributes
 *
 * @return object
 */
function create_component_with_layout_container_size(array $attributes): object {
    return new class($attributes) {
        use LayoutContainerSize;

        public function __construct(array $attributes) {
            $this->set_size_from_attrs($attributes);
        }

        public function get_size() {
            return $this->size;
        }
    };
}

beforeEach(function() {
    Config::init();
});

afterEach(function() {
    restoreAll();
});

test('sets valid value from a ContainerSize attribute', function() {
    $component = create_component_with_layout_container_size(['size' => ContainerSize::WIDE]);

    expect($component->get_size())->toBe(ContainerSize::WIDE);
});

test('sets valid value from a string attribute', function() {
    $component = create_component_with_layout_container_size(['size' => 'narrow']);

    expect($component->get_size())->toBe(ContainerSize::NARROW);
});

test('it uses component default if an attribute is not provided', function() {
    redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['size' => 'wide']);

    $component = create_component_with_layout_container_size([]);

    expect($component->get_size())->toBe(ContainerSize::WIDE);
});

test('ignores component default if a valid attribute value is provided', function() {
	redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['size' => 'narrow']);

	$component = create_component_with_layout_container_size(['size' => 'wide']);

	expect($component->get_size())->toBe(ContainerSize::WIDE);
});

test('uses component default if the provided attribute is invalid', function() {
	redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['size' => 'narrow']);

	$component = create_component_with_layout_container_size(['size' => 'invalid']);

	expect($component->get_size())->toBe(ContainerSize::NARROW);
});

test('ignores invalid attribute values and falls back to trait default if there is no component default', function() {
    $component = create_component_with_layout_container_size(['size' => 'invalid']);

    expect($component->get_size())->toBe(ContainerSize::DEFAULT);
});

