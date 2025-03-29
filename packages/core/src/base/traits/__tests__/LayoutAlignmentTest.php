<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core\__tests__;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use Doubleedesign\Comet\Core\{LayoutAlignment, Alignment};
use function Phluent\Expect;

#[TestDox("LayoutAlignment")]
class LayoutAlignmentTest extends TestCase {
	/**
	 * Function to create a generic component class that uses the trait
	 * allowing it to stay local to this test/file
	 * @param array $attributes
	 * @return object
	 */
	private function create_component(array $attributes): object {
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

	#[TestDox('It sets the horizontal alignment when a valid attribute is passed')]
	#[Test] public function sets_valid_horizontal_value() {
		$component = $this->create_component(['justifyContent' => 'center']);

		Expect($component->get_hAlign())->toBe(Alignment::CENTER);
	}

	#[TestDox('It sets the horizontal alignment when a valid attribute is passed in WordPress layout format')]
	#[Test] public function sets_valid_horizontal_value_from_wp_layout() {
		$component = $this->create_component(['layout' => ['justifyContent' => 'center']]);

		Expect($component->get_hAlign())->toBe(Alignment::CENTER);
	}

	#[TestDox('It sets the vertical alignment when a valid attribute is passed')]
	#[Test] public function sets_valid_vertical_value() {
		$component = $this->create_component(['alignItems' => 'center']);

		Expect($component->get_vAlign())->toBe(Alignment::CENTER);
	}

	#[TestDox('It sets the vertical alignment when a valid attribute is passed in WordPress layout format')]
	#[Test] public function sets_valid_vertical_value_from_wp_layout() {
		$component = $this->create_component(['layout' => ['alignItems' => 'center']]);

		Expect($component->get_vAlign())->toBe(Alignment::CENTER);
	}

	#[TestDox('It sets the vertical alignment when a valid attribute is passed in WordPress format')]
	#[Test] public function sets_valid_vertical_value_from_wp() {
		$component = $this->create_component(['verticalAlignment' => 'center']);

		Expect($component->get_vAlign())->toBe(Alignment::CENTER);
	}

	#[TestDox('It sets null when an invalid hAlign value is passed')]
	#[Test] public function sets_null_horizontal_value() {
		$component = $this->create_component(['hAlign' => 'invalid']);

		Expect($component->get_hAlign())->toBeNull();
	}

	#[TestDox('It sets null when an invalid vAlign value is passed')]
	#[Test] public function sets_null_vertical_value() {
		$component = $this->create_component(['vAlign' => 'invalid']);

		Expect($component->get_vAlign())->toBeNull();
	}
}
