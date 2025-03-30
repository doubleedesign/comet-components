<?php
use Doubleedesign\Comet\Core\{ColorTheme, ThemeColor};
use function Phluent\Expect;


/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 * @param array $attributes
 * @return object
 */
function create_component_with_color_theme(array $attributes): object {
	return new class($attributes) {
		use ColorTheme;

		function __construct(array $attributes) {
			$this->set_color_theme_from_attrs($attributes);
		}

		function get_color_theme() {
			return $this->colorTheme;
		}
	};
}

test('sets valid value', function() {
	$component = create_component_with_color_theme(['colorTheme' => 'secondary']);

	Expect($component->get_color_theme())->toBe(ThemeColor::SECONDARY);
});

test('sets null background color', function() {
	$component = create_component_with_color_theme(['colorTheme' => '#FFF']);

	Expect($component->get_color_theme())->toBeNull();
});
