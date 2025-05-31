<?php

use Doubleedesign\Comet\Core\Alignment;

describe('Alignment', function() {
    it('returns START for "start", "left" or "top"', function() {
        foreach (['start', 'left', 'top'] as $value) {
            $alignment = Alignment::fromString($value);
            expect($alignment)->toBe(Alignment::START);
        }
    });

    it('returns END for "end", "right" or "bottom"', function() {
        foreach (['end', 'right', 'bottom'] as $value) {
            $alignment = Alignment::fromString($value);
            expect($alignment)->toBe(Alignment::END);
        }
    });

    it('returns CENTER for "center"', function() {
        $alignment = Alignment::fromString('center');
        expect($alignment)->toBe(Alignment::CENTER);
    });

    it('returns JUSTIFY for "justify"', function() {
        $alignment = Alignment::fromString('justify');
        expect($alignment)->toBe(Alignment::JUSTIFY);
    });

    it('returns MATCH_PARENT for "match-parent"', function() {
        $alignment = Alignment::fromString('match-parent');
        expect($alignment)->toBe(Alignment::MATCH_PARENT);
    });

    it('returns MATCH_PARENT when an invalid value is passed', function() {
        $alignment = Alignment::fromString('invalid');
        expect($alignment)->toBe(Alignment::MATCH_PARENT);
    });
});
