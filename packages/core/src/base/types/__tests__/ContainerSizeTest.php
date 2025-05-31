<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\ContainerSize;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};

#[TestDox("ContainerSize")]
class ContainerSizeTest extends TestCase {
    #[TestDox('It returns WIDE for "wide"')]
    #[Test] public function from_string_wide() {
        $result = ContainerSize::tryFrom('wide');
        expect($result)->toBe(ContainerSize::WIDE);
    }

    #[TestDox('It returns FULLWIDTH for "fullwidth"')]
    #[Test] public function from_string_fullwidth() {
        $result = ContainerSize::tryFrom('fullwidth');
        expect($result)->toBe(ContainerSize::FULLWIDTH);
    }

    #[TestDox('It returns NARROW for "narrow"')]
    #[Test] public function from_string_narrow() {
        $result = ContainerSize::tryFrom('narrow');
        expect($result)->toBe(ContainerSize::NARROW);
    }

    #[TestDox('It returns NARROWER for "narrower"')]
    #[Test] public function from_string_narrower() {
        $result = ContainerSize::tryFrom('narrower');
        expect($result)->toBe(ContainerSize::NARROWER);
    }

    #[TestDox('It returns DEFAULT for "default"')]
    #[Test] public function from_string_default() {
        $result = ContainerSize::tryFrom('default');
        expect($result)->toBe(ContainerSize::DEFAULT);
    }

    #[TestDox('It returns null when an invalid value is passed')]
    #[Test] public function from_string_invalid() {
        $result = ContainerSize::tryFrom('invalid');
        expect($result)->toBeNull();
    }

    #[TestDox('It returns WIDE for WordPress wide style')]
    #[Test] public function from_wordpress_class_name_wide() {
        $result = ContainerSize::from_wordpress_class_name('is-style-wide');
        expect($result)->toBe(ContainerSize::WIDE);
    }

    #[TestDox('It returns FULLWIDTH for WordPress fullwidth style')]
    #[Test] public function from_wordpress_class_name_fullwidth() {
        $result = ContainerSize::from_wordpress_class_name('is-style-fullwidth');
        expect($result)->toBe(ContainerSize::FULLWIDTH);
    }

    #[TestDox('It returns NARROW for WordPress narrow style')]
    #[Test] public function from_wordpress_class_name_narrow() {
        $result = ContainerSize::from_wordpress_class_name('is-style-narrow');
        expect($result)->toBe(ContainerSize::NARROW);
    }

    #[TestDox('It returns DEFAULT if an unsupported WordPress style is passed')]
    #[Test] public function from_wordpress_class_name_default() {
        $result = ContainerSize::from_wordpress_class_name('is-style-unsupported');
        expect($result)->toBe(ContainerSize::DEFAULT);
    }
}
