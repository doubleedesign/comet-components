<?php
use Doubleedesign\Comet\Core\{BackgroundColor, ThemeColor};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 * @param array $attributes
 * @return object
 */
function create_component_with_bg_color(array $attributes): object {
	return new class($attributes) {
		use BackgroundColor;

		function __construct(array $attributes) {
			$this->set_background_color_from_attrs($attributes);
		}

		function get_background_color() {
			return $this->backgroundColor;
		}
	};
}

/**
 * Function to create a component class that uses the trait and can handle inner components
 * @param array $attributes
 * @param array $innerComponents
 * @return object
 */
function create_component_with_inner_components(array $attributes = [], array $innerComponents = []): object {
	return new class($attributes, $innerComponents) {
		use BackgroundColor;

		public array $innerComponents = [];

		function __construct(array $attributes, array $innerComponents = []) {
			$this->set_background_color_from_attrs($attributes);
			$this->innerComponents = $innerComponents;
		}
	};
}

describe('Set background color from attributes', function() {
	$spy = Mockery::spy(BackgroundColor::class);
	$spy->shouldAllowMockingProtectedMethods();

	test('sets valid value', function() use ($spy) {
		$component = create_component_with_bg_color(['backgroundColor' => 'secondary']);

		$spy->shouldReceive('set_background_color_from_attrs');
		expect($component->get_background_color())->toBe(ThemeColor::SECONDARY);
	});

	test('sets null when invalid', function() use ($spy) {
		$component = create_component_with_bg_color(['backgroundColor' => '#FFF']);

		$spy->shouldReceive('set_background_color_from_attrs');
		expect($component->get_background_color())->toBeNull();
	});
});

describe('Remove redundant background colours from direct inner components', function() {
	$spy = Mockery::spy(BackgroundColor::class);
	$spy->shouldAllowMockingProtectedMethods();

	test('it removes background when all backgrounds match the component', function() use ($spy) {
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'primary']);

		$parent = create_component_with_inner_components(
			['backgroundColor' => 'primary'],
			[$child1, $child2, $child3]
		);

		$parent->simplify_all_background_colors();

		$spy->shouldReceive('remove_redundant_background_colors');
		expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY);
		foreach($parent->innerComponents as $child) {
			expect($child->get_background_color())->toBeNull();
		}
	});

	test('it removes only the same background when inner components are mixed', function() use ($spy) {
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'accent']);

		$parent = create_component_with_inner_components(
			['backgroundColor' => 'primary'],
			[$child1, $child2, $child3]
		);

		$parent->simplify_all_background_colors();


		$spy->shouldReceive('remove_redundant_background_colors');
		expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY)
			->and($parent->innerComponents[0]->get_background_color())->toBeNull()
			->and($parent->innerComponents[1]->get_background_color())->toBe(ThemeColor::SECONDARY)
			->and($parent->innerComponents[2]->get_background_color())->toBe(ThemeColor::ACCENT);
	});

	test('it does nothing if there is only one inner component', function() use ($spy) {
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);

		$parent = create_component_with_inner_components(
			['backgroundColor' => 'primary'],
			[$child1]
		);

		$parent->simplify_all_background_colors();

		$spy->shouldNotHaveReceived('remove_redundant_background_colors');
		expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY)
			->and($parent->innerComponents[0]->get_background_color())->toBe(ThemeColor::PRIMARY);
	});
});

describe('Set background colour based on inner components', function() {
	$spy = Mockery::spy(BackgroundColor::class);
	$spy->shouldAllowMockingProtectedMethods();

	test('it sets a background when all inner components have the same background', function() use ($spy) {
		$child1 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'secondary']);

		$parent = create_component_with_inner_components(
			[], // No background
			[$child1, $child2, $child3]
		);

		$parent->simplify_all_background_colors();

		$spy->shouldReceive('set_background_color_based_on_inner_components');
		expect($parent->get_background_color())->toBe(ThemeColor::SECONDARY)
			->and($parent->innerComponents[0]->get_background_color())->toBeNull()
			->and($parent->innerComponents[1]->get_background_color())->toBeNull()
			->and($parent->innerComponents[2]->get_background_color())->toBeNull();
	});

	test('it does nothing when children have different backgrounds', function() use ($spy) {
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'accent']);

		$parent = create_component_with_inner_components(
			[], // No background
			[$child1, $child2, $child3]
		);

		$parent->simplify_all_background_colors();

		$spy->shouldReceive('set_background_color_based_on_inner_components');
		expect($parent->get_background_color())->toBeNull()
			->and($parent->innerComponents[0]->get_background_color())->toBe(ThemeColor::PRIMARY)
			->and($parent->innerComponents[1]->get_background_color())->toBe(ThemeColor::SECONDARY)
			->and($parent->innerComponents[2]->get_background_color())->toBe(ThemeColor::ACCENT);
	});

	test('it sets a background when the inner components have a mix of the same and no backgrounds set', function() use ($spy) {
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color([]); // Null background
		$child3 = create_component_with_bg_color(['backgroundColor' => 'primary']);

		$parent = create_component_with_inner_components(
			[], // No background
			[$child1, $child2, $child3]
		);

		$parent->simplify_all_background_colors();

		$spy->shouldReceive('set_background_color_based_on_inner_components');
		expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY)
			->and($parent->innerComponents[0]->get_background_color())->toBeNull()
			->and($parent->innerComponents[1]->get_background_color())->toBeNull()
			->and($parent->innerComponents[2]->get_background_color())->toBeNull();
	});

	test('it does nothing if there is only one inner component', function() use ($spy) {
		$child = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$parent = create_component_with_inner_components(
			[], // No background
			[$child]
		);

		$parent->simplify_all_background_colors();

		$spy->shouldNotHaveReceived('set_background_color_based_on_inner_components');
		expect($parent->get_background_color())->toBeNull()
			->and($parent->innerComponents[0]->get_background_color())->toBe(ThemeColor::PRIMARY);
	});
});





