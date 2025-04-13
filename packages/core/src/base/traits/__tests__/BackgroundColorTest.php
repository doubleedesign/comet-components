<?php
use Doubleedesign\Comet\Core\{BackgroundColor, ThemeColor};
use function Phluent\Expect;

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
	test('sets valid value', function() {
		$component = create_component_with_bg_color(['backgroundColor' => 'secondary']);

		Expect($component->get_background_color())->toBe(ThemeColor::SECONDARY);
	});

	test('sets null when invalid', function() {
		$component = create_component_with_bg_color(['backgroundColor' => '#FFF']);

		Expect($component->get_background_color())->toBeNull();
	});
});

describe('Remove redundant background colours from direct inner components', function() {
	test('it removes background when all backgrounds match the component', function() {
		// Create child components with the same background
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'primary']);

		// Create parent with same background and the children
		$parent = create_component_with_inner_components(
			['backgroundColor' => 'primary'],
			[$child1, $child2, $child3]
		);

		// Run the simplification
		$parent->simplify_all_background_colors();

		// Check that parent still has the background
		Expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY);

		// Check that all children's backgrounds are now null
		foreach($parent->innerComponents as $child) {
			Expect($child->get_background_color())->toBeNull();
		}
	});

	test('it removes only the same background when inner components are mixed', function() {
		// Create child components with different backgrounds
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'accent']);

		// Create parent with a background and the children
		$parent = create_component_with_inner_components(
			['backgroundColor' => 'primary'],
			[$child1, $child2, $child3]
		);

		// Run the simplification
		$parent->simplify_all_background_colors();

		// Check that parent still has the background
		Expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY);

		Expect($parent->innerComponents[0]->get_background_color())->toBeNull();
		Expect($parent->innerComponents[1]->get_background_color())->toBe(ThemeColor::SECONDARY);
		Expect($parent->innerComponents[2]->get_background_color())->toBe(ThemeColor::ACCENT);
	});
});

describe('Set background colour based on inner components', function() {
	test('it sets a background when all inner components have the same background', function() {
		// Create child components with the same background
		$child1 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'secondary']);

		// Create parent with no background and the children
		$parent = create_component_with_inner_components(
			[], // No background
			[$child1, $child2, $child3]
		);

		// Run the simplification
		$parent->simplify_all_background_colors();

		// Check that parent now has the background from children
		Expect($parent->get_background_color())->toBe(ThemeColor::SECONDARY);

		// Check that all children's backgrounds are now null
		foreach($parent->innerComponents as $child) {
			Expect($child->get_background_color())->toBeNull();
		}
	});

	test('it does nothing when children have different backgrounds', function() {
		// Create child components with different backgrounds
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
		$child3 = create_component_with_bg_color(['backgroundColor' => 'accent']);

		// Create parent with no background and the children
		$parent = create_component_with_inner_components(
			[], // No background
			[$child1, $child2, $child3]
		);

		// Run the simplification
		$parent->simplify_all_background_colors();

		// Check that parent still has no background
		Expect($parent->get_background_color())->toBeNull();

		// Check that children backgrounds are unchanged
		Expect($parent->innerComponents[0]->get_background_color())->toBe(ThemeColor::PRIMARY);
		Expect($parent->innerComponents[1]->get_background_color())->toBe(ThemeColor::SECONDARY);
		Expect($parent->innerComponents[2]->get_background_color())->toBe(ThemeColor::ACCENT);
	});

	test('it sets a background when the inner components have a mix of the same and no backgrounds set', function() {
		// Create child components - some with the same background, some with null
		$child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
		$child2 = create_component_with_bg_color([]); // Null background
		$child3 = create_component_with_bg_color(['backgroundColor' => 'primary']);

		// Create parent with no background and the children
		$parent = create_component_with_inner_components(
			[], // No background
			[$child1, $child2, $child3]
		);

		// Run the simplification
		$parent->simplify_all_background_colors();

		// Check that parent now has the background from children
		Expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY);

		// Check that all children's backgrounds are now null
		foreach($parent->innerComponents as $child) {
			Expect($child->get_background_color())->toBeNull();
		}
	});
});





