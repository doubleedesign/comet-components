<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\Alignment;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};

#[TestDox("Alignment")]
class AlignmentTest extends TestCase {

    #[TestDox('It returns START for "start", "left" or "top"')]
    #[Test] public function from_string_start() {
        foreach (['start', 'left', 'top'] as $value) {
            $alignment = Alignment::fromString($value);
            expect($alignment)->toBe(Alignment::START);
        }
    }

    #[TestDox('It returns END for "end", "right" or "bottom"')]
    #[Test] public function from_string_end() {
        foreach (['end', 'right', 'bottom'] as $value) {
            $alignment = Alignment::fromString($value);
            expect($alignment)->toBe(Alignment::END);
        }
    }

    #[TestDox('It returns CENTER for "center"')]
    #[Test] public function from_string_center() {
        $alignment = Alignment::fromString('center');
        expect($alignment)->toBe(Alignment::CENTER);
    }

    #[TestDox('It returns JUSTIFY for "justify"')]
    #[Test] public function from_string_justify() {
        $alignment = Alignment::fromString('justify');
        expect($alignment)->toBe(Alignment::JUSTIFY);
    }

    #[TestDox('It returns MATCH_PARENT for "match-parent"')]
    #[Test] public function from_string_match_parent() {
        $alignment = Alignment::fromString('match-parent');
        expect($alignment)->toBe(Alignment::MATCH_PARENT);
    }

    #[TestDox('It returns MATCH_PARENT when an invalid value is passed')]
    #[Test] public function from_string_default() {
        $alignment = Alignment::fromString('invalid');
        expect($alignment)->toBe(Alignment::MATCH_PARENT);
    }
}
