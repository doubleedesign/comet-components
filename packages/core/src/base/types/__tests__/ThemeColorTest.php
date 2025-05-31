<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\ThemeColor;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};

#[TestDox("ThemeColor")]
class ThemeColorTest extends TestCase {
    #[TestDox('It returns PRIMARY for "primary"')]
    #[Test] public function from_string_primary() {
        $result = ThemeColor::tryFrom('primary');
        expect($result)->toBe(ThemeColor::PRIMARY);
    }

    #[TestDox('It returns SECONDARY for "secondary"')]
    #[Test] public function from_string_secondary() {
        $result = ThemeColor::tryFrom('secondary');
        expect($result)->toBe(ThemeColor::SECONDARY);
    }

    #[TestDox('It returns ACCENT for "accent"')]
    #[Test] public function from_string_accent() {
        $result = ThemeColor::tryFrom('accent');
        expect($result)->toBe(ThemeColor::ACCENT);
    }

    #[TestDox('It returns SUCCESS for "success"')]
    #[Test] public function from_string_success() {
        $result = ThemeColor::tryFrom('success');
        expect($result)->toBe(ThemeColor::SUCCESS);
    }

    #[TestDox('It returns WARNING for "warning"')]
    #[Test] public function from_string_warning() {
        $result = ThemeColor::tryFrom('warning');
        expect($result)->toBe(ThemeColor::WARNING);
    }

    #[TestDox('It returns INFO for "info"')]
    #[Test] public function from_string_info() {
        $result = ThemeColor::tryFrom('info');
        expect($result)->toBe(ThemeColor::INFO);
    }

    #[TestDox('It returns LIGHT for "light"')]
    #[Test] public function from_string_light() {
        $result = ThemeColor::tryFrom('light');
        expect($result)->toBe(ThemeColor::LIGHT);
    }

    #[TestDox('It returns DARK for "dark"')]
    #[Test] public function from_string_dark() {
        $result = ThemeColor::tryFrom('dark');
        expect($result)->toBe(ThemeColor::DARK);
    }

    #[TestDox('It returns WHITE for "white"')]
    #[Test] public function from_string_white() {
        $result = ThemeColor::tryFrom('white');
        expect($result)->toBe(ThemeColor::WHITE);
    }

    #[TestDox('It returns null when an invalid value is passed')]
    #[Test] public function from_string_invalid() {
        $result = ThemeColor::tryFrom('invalid');
        expect($result)->toBeNull();
    }
}
