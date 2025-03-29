<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core\__tests__;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use Doubleedesign\Comet\Core\{ColorTheme, ThemeColor};
use function Phluent\Expect;

#[TestDox("ColorTheme")]
class ColorThemeTest extends TestCase {
	/**
	 * Function to create a generic component class that uses the trait
	 * allowing it to stay local to this test/file
	 * @param array $attributes
	 * @return object
	 */
	private function create_component(array $attributes): object {
		return new class($attributes) {
			use ColorTheme;

			public function __construct(array $attributes) {
				$this->set_color_theme_from_attrs($attributes);
			}

			public function get_color_theme() {
				return $this->colorTheme;
			}
		};
	}

	#[TestDox('It sets the background colour when a valid name is passed')]
	#[Test] public function sets_valid_value() {
		$component = $this->create_component(['colorTheme' => 'secondary']);

		Expect($component->get_color_theme())->toBe(ThemeColor::SECONDARY);
	}

	#[TestDox('It sets null when an invalid value is passed and there is no default')]
	#[Test] public function sets_null_background_color() {
		$component = $this->create_component(['colorTheme' => '#FFF']);

		Expect($component->get_color_theme())->toBeNull();
	}
}
