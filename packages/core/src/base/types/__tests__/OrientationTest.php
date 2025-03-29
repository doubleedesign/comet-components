<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\Orientation;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use function Phluent\Expect;

#[TestDox("Orientation")]
class OrientationTest extends TestCase {
	#[TestDox('It returns HORIZONTAL for "horizontal"')]
	#[Test] public function from_string_horizontal() {
		$result = Orientation::tryFrom('horizontal');
		Expect($result)->toBe(Orientation::HORIZONTAL);
	}

	#[TestDox('It returns VERTICAL for "vertical"')]
	#[Test] public function from_string_vertical() {
		$result = Orientation::tryFrom('vertical');
		Expect($result)->toBe(Orientation::VERTICAL);
	}

	#[TestDox('It returns null when an invalid value is passed')]
	#[Test] public function from_string_invalid() {
		$result = Orientation::tryFrom('invalid');
		Expect($result)->toBeNull();
	}
}
