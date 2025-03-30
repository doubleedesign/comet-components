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

test('sets valid value', function() {
	$component = create_component_with_bg_color(['backgroundColor' => 'secondary']);

	Expect($component->get_background_color())->toBe(ThemeColor::SECONDARY);
});

test('sets null when invalid', function() {
	$component = create_component_with_bg_color(['backgroundColor' => '#FFF']);

	Expect($component->get_background_color())->toBeNull();
});
