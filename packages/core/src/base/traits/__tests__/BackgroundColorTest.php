<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core\__tests__;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use Doubleedesign\Comet\Core\{BackgroundColor, ThemeColor};
use function Phluent\Expect;

#[TestDox("BackgroundColor")]
class BackgroundColorTest extends TestCase {
	/**
	 * Function to create a generic component class that uses the trait
	 * allowing it to stay local to this test/file
	 * @param array $attributes
	 * @return object
	 */
	private function create_component(array $attributes): object {
		return new class($attributes) {
			use BackgroundColor;

			public function __construct(array $attributes) {
				$this->set_background_color_from_attrs($attributes);
			}

			public function get_background_color() {
				return $this->backgroundColor;
			}
		};
	}

	#[TestDox('It sets the background colour when a valid name is passed')]
	#[Test] public function sets_valid_value() {
		$component = $this->create_component(['backgroundColor' => 'secondary']);

		Expect($component->get_background_color())->toBe(ThemeColor::SECONDARY);
	}

	#[TestDox('It sets null when an invalid value is passed')]
	#[Test] public function sets_null_when_invalid() {
		$component = $this->create_component(['backgroundColor' => '#FFF']);

		Expect($component->get_background_color())->toBeNull();
	}
}
