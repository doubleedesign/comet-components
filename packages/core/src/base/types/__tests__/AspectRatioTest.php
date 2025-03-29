<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\AspectRatio;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use function Phluent\Expect;

#[TestDox("AspectRatio")]
class AspectRatioTest extends TestCase {

	#[TestDox('It returns STANDARD for 4:3')]
	#[Test] public function from_string_4_3() {
		$result = AspectRatio::tryFrom('4:3');
		Expect($result)->toBe(AspectRatio::STANDARD);
	}

	#[TestDox('It returns PORTRAIT for 3:4')]
	#[Test] public function from_string_3_4() {
		$result = AspectRatio::tryFrom('3:4');
		Expect($result)->toBe(AspectRatio::PORTRAIT);
	}

	#[TestDox('It returns SQUARE for 1:1')]
	#[Test] public function from_string_1_1() {
		$result = AspectRatio::tryFrom('1:1');
		Expect($result)->toBe(AspectRatio::SQUARE);
	}

	#[TestDox('It returns WIDE for 16:9')]
	#[Test] public function from_string_16_9() {
		$result = AspectRatio::tryFrom('16:9');
		Expect($result)->toBe(AspectRatio::WIDE);
	}

	#[TestDox('It returns TALL for 9:16')]
	#[Test] public function from_string_9_16() {
		$result = AspectRatio::tryFrom('9:16');
		Expect($result)->toBe(AspectRatio::TALL);
	}

	#[TestDox('It returns CLASSIC for 3:2')]
	#[Test] public function from_string_3_2() {
		$result = AspectRatio::tryFrom('3:2');
		Expect($result)->toBe(AspectRatio::CLASSIC);
	}

	#[TestDox('It returns CLASSIC_PORTRAIT for 2:3')]
	#[Test] public function from_string_2_3() {
		$result = AspectRatio::tryFrom('2:3');
		Expect($result)->toBe(AspectRatio::CLASSIC_PORTRAIT);
	}

	#[TestDox('It returns null when an invalid value is passed')]
	#[Test] public function from_string_invalid() {
		$result = AspectRatio::tryFrom('invalid');
		Expect($result)->toBeNull();
	}
}
