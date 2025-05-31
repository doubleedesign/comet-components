<?php

use Doubleedesign\Comet\Core\AspectRatio;

describe('AspectRatio', function() {
    it('returns STANDARD for 4:3', function() {
        $result = AspectRatio::tryFrom('4:3');
        expect($result)->toBe(AspectRatio::STANDARD);
    });

    it('returns PORTRAIT for 3:4', function() {
        $result = AspectRatio::tryFrom('3:4');
        expect($result)->toBe(AspectRatio::PORTRAIT);
    });

    it('returns SQUARE for 1:1', function() {
        $result = AspectRatio::tryFrom('1:1');
        expect($result)->toBe(AspectRatio::SQUARE);
    });

    it('returns WIDE for 16:9', function() {
        $result = AspectRatio::tryFrom('16:9');
        expect($result)->toBe(AspectRatio::WIDE);
    });

    it('returns TALL for 9:16', function() {
        $result = AspectRatio::tryFrom('9:16');
        expect($result)->toBe(AspectRatio::TALL);
    });

    it('returns CLASSIC for 3:2', function() {
        $result = AspectRatio::tryFrom('3:2');
        expect($result)->toBe(AspectRatio::CLASSIC);
    });

    it('returns CLASSIC_PORTRAIT for 2:3', function() {
        $result = AspectRatio::tryFrom('2:3');
        expect($result)->toBe(AspectRatio::CLASSIC_PORTRAIT);
    });

    it('returns null when an invalid value is passed', function() {
        $result = AspectRatio::tryFrom('invalid');
        expect($result)->toBeNull();
    });
});
