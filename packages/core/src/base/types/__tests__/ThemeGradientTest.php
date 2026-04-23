<?php
use Doubleedesign\Comet\Core\{ThemeGradient,ThemeColor};

test('two valid ThemeColor values', function() {
    $instance = new ThemeGradient(ThemeColor::LIGHT, ThemeColor::DARK);

    expect($instance)->toEqual('light-dark');
});

test('two valid string values', function() {
    $instance = new ThemeGradient('primary', 'secondary');

    expect($instance)->toEqual('primary-secondary');
});

it('returns empty if a valid string from value is provided with an invalid to value', function() {
    $instance = new ThemeGradient('primary', 'invalid');

    expect($instance)->toEqual('');
});

it('returns empty if a valid ThemeColor from value is provided with an invalid to value', function() {
    $instance = new ThemeGradient(ThemeColor::ACCENT, 'invalid');

    expect($instance)->toEqual('');
});

it('returns empty if an invalid from value is provided with a valid string to value', function() {
    $instance = new ThemeGradient('invalid', 'secondary');

    expect($instance)->toEqual('');
});

it('returns empty if an invalid from value is provided with a valid ThemeColor to value', function() {
    $instance = new ThemeGradient('invalid', ThemeColor::LIGHT);

    expect($instance)->toEqual('');
});

it('returns empty if two invalid strings are provided', function() {
    $instance = new ThemeGradient('invalid', 'invalid');

    expect($instance)->toEqual('');
});
