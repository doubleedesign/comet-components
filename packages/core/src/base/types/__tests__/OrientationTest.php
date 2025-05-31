<?php

use Doubleedesign\Comet\Core\Orientation;

describe('Orientation', function() {
    it('returns HORIZONTAL for "horizontal"', function() {
        $result = Orientation::tryFrom('horizontal');
        expect($result)->toBe(Orientation::HORIZONTAL);
    });

    it('returns VERTICAL for "vertical"', function() {
        $result = Orientation::tryFrom('vertical');
        expect($result)->toBe(Orientation::VERTICAL);
    });

    it('returns null when an invalid value is passed', function() {
        $result = Orientation::tryFrom('invalid');
        expect($result)->toBeNull();
    });
});
