<?php

use Doubleedesign\Comet\Core\ContainerSize;

describe('ContainerSize', function() {
    it('returns WIDE for "wide"', function() {
        $result = ContainerSize::tryFrom('wide');
        expect($result)->toBe(ContainerSize::WIDE);
    });

    it('returns FULLWIDTH for "fullwidth"', function() {
        $result = ContainerSize::tryFrom('fullwidth');
        expect($result)->toBe(ContainerSize::FULLWIDTH);
    });

    it('returns NARROW for "narrow"', function() {
        $result = ContainerSize::tryFrom('narrow');
        expect($result)->toBe(ContainerSize::NARROW);
    });

    it('returns NARROWER for "narrower"', function() {
        $result = ContainerSize::tryFrom('narrower');
        expect($result)->toBe(ContainerSize::NARROWER);
    });

    it('returns DEFAULT for "default"', function() {
        $result = ContainerSize::tryFrom('default');
        expect($result)->toBe(ContainerSize::DEFAULT);
    });

    it('returns null when an invalid value is passed', function() {
        $result = ContainerSize::tryFrom('invalid');
        expect($result)->toBeNull();
    });

    it('returns WIDE for WordPress wide style', function() {
        $result = ContainerSize::from_wordpress_class_name('is-style-wide');
        expect($result)->toBe(ContainerSize::WIDE);
    });

    it('returns FULLWIDTH for WordPress fullwidth style', function() {
        $result = ContainerSize::from_wordpress_class_name('is-style-fullwidth');
        expect($result)->toBe(ContainerSize::FULLWIDTH);
    });

    it('returns NARROW for WordPress narrow style', function() {
        $result = ContainerSize::from_wordpress_class_name('is-style-narrow');
        expect($result)->toBe(ContainerSize::NARROW);
    });

    it('returns DEFAULT if an unsupported WordPress style is passed', function() {
        $result = ContainerSize::from_wordpress_class_name('is-style-unsupported');
        expect($result)->toBe(ContainerSize::DEFAULT);
    });
});
