<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\Alignment;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use function Phluent\Expect;

#[TestDox("Alignment")]
class AlignmentTest extends TestCase {

    #[TestDox('It returns START for "start", "left" or "top"')]
    #[Test] public function from_string_start() {
        foreach (['start', 'left', 'top'] as $value) {
            $alignment = Alignment::fromString($value);
            Expect($alignment)->toBe(Alignment::START);
        }
    }

    #[TestDox('It returns END for "end", "right" or "bottom"')]
    #[Test] public function from_string_end() {
        foreach (['end', 'right', 'bottom'] as $value) {
            $alignment = Alignment::fromString($value);
            Expect($alignment)->toBe(Alignment::END);
        }
    }

    #[TestDox('It returns CENTER for "center"')]
    #[Test] public function from_string_center() {
        $alignment = Alignment::fromString('center');
        Expect($alignment)->toBe(Alignment::CENTER);
    }

    #[TestDox('It returns JUSTIFY for "justify"')]
    #[Test] public function from_string_justify() {
        $alignment = Alignment::fromString('justify');
        Expect($alignment)->toBe(Alignment::JUSTIFY);
    }

    #[TestDox('It returns MATCH_PARENT for "match-parent"')]
    #[Test] public function from_string_match_parent() {
        $alignment = Alignment::fromString('match-parent');
        Expect($alignment)->toBe(Alignment::MATCH_PARENT);
    }

    #[TestDox('It returns MATCH_PARENT when an invalid value is passed')]
    #[Test] public function from_string_default() {
        $alignment = Alignment::fromString('invalid');
        Expect($alignment)->toBe(Alignment::MATCH_PARENT);
    }
}
