<?php

use Doubleedesign\Comet\Core\ThemeColor;

describe('ThemeColor', function() {
    it('returns PRIMARY for "primary"', function() {
        $result = ThemeColor::tryFrom('primary');
        expect($result)->toBe(ThemeColor::PRIMARY);
    });

    it('returns SECONDARY for "secondary"', function() {
        $result = ThemeColor::tryFrom('secondary');
        expect($result)->toBe(ThemeColor::SECONDARY);
    });

    it('returns ACCENT for "accent"', function() {
        $result = ThemeColor::tryFrom('accent');
        expect($result)->toBe(ThemeColor::ACCENT);
    });

    it('returns SUCCESS for "success"', function() {
        $result = ThemeColor::tryFrom('success');
        expect($result)->toBe(ThemeColor::SUCCESS);
    });

    it('returns WARNING for "warning"', function() {
        $result = ThemeColor::tryFrom('warning');
        expect($result)->toBe(ThemeColor::WARNING);
    });

    it('returns INFO for "info"', function() {
        $result = ThemeColor::tryFrom('info');
        expect($result)->toBe(ThemeColor::INFO);
    });

    it('returns LIGHT for "light"', function() {
        $result = ThemeColor::tryFrom('light');
        expect($result)->toBe(ThemeColor::LIGHT);
    });

    it('returns DARK for "dark"', function() {
        $result = ThemeColor::tryFrom('dark');
        expect($result)->toBe(ThemeColor::DARK);
    });

    it('returns WHITE for "white"', function() {
        $result = ThemeColor::tryFrom('white');
        expect($result)->toBe(ThemeColor::WHITE);
    });

    it('returns null when an invalid value is passed', function() {
        $result = ThemeColor::tryFrom('invalid');
        expect($result)->toBeNull();
    });
});
