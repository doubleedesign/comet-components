<?php

use Doubleedesign\Comet\Core\{BackgroundCollection, ThemeColor, ThemeGradient};

describe('Standard instance creation', function() {
    test('outer value is set if only one value is provided (string)', function() {
        $instance = new BackgroundCollection('primary');

        expect($instance->outer)->toBe(ThemeColor::PRIMARY)
            ->and($instance->inner)->toBeNull();
    });

    test('outer value is set if only one value is provided (ThemeColor)', function() {
        $instance = new BackgroundCollection(ThemeColor::SECONDARY);

        expect($instance->outer)->toBe(ThemeColor::SECONDARY)
            ->and($instance->inner)->toBeNull();
    });

    test('outer value is set if only one value is provided (ThemeGradient)', function() {
        $instance = new BackgroundCollection(new ThemeGradient(ThemeColor::DARK, ThemeColor::LIGHT));

        expect($instance->outer->value)->toBe('dark-light')
            ->and($instance->inner)->toBeNull();
    });

    test('outer value is set if only one value is provided (gradient string)', function() {
        $instance = new BackgroundCollection('accent-white');

        expect($instance->outer->value)->toBe('accent-white')
            ->and($instance->inner)->toBeNull();
    });

    test('invalid value provided for outer', function() {
        $instance = new BackgroundCollection('#FFF');

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBeNull();
    });

    test('only inner value provided (string)', function() {
        $instance = new BackgroundCollection(null, 'primary');

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBe(ThemeColor::PRIMARY);
    });

    test('only inner value provided (ThemeColor)', function() {
        $instance = new BackgroundCollection(null, ThemeColor::SECONDARY);

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBe(ThemeColor::SECONDARY);
    });

    test('invalid string value provided for inner', function() {
        $instance = new BackgroundCollection(null, '#FFF');

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBeNull();
    });

    test('inner cannot be a gradient (string provided)', function() {
        $instance = new BackgroundCollection(null, 'primary-to-secondary');

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBeNull();
    });

    test('inner cannot be a gradient (object provided)', function() {
        $instance = new BackgroundCollection(null, new ThemeGradient(ThemeColor::PRIMARY, ThemeColor::SECONDARY));

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBeNull();
    });

    it('returns ThemeColors for both values when two valid string values provided', function() {
        $instance = new BackgroundCollection('primary', 'secondary');

        expect($instance->outer)->toBe(ThemeColor::PRIMARY)
            ->and($instance->inner)->toBe(ThemeColor::SECONDARY);
    });

    it('returns null for both values when two invalid string values provided', function() {
        $instance = new BackgroundCollection('#FFF', '#000');

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBeNull();
    });

    test('valid outer, invalid inner', function() {
        $instance = new BackgroundCollection('primary', '#000');

        expect($instance->outer)->toBe(ThemeColor::PRIMARY)
            ->and($instance->inner)->toBeNull();
    });

    test('invalid outer, valid inner', function() {
        $instance = new BackgroundCollection('#FFF', 'secondary');

        expect($instance->outer)->toBeNull()
            ->and($instance->inner)->toBe(ThemeColor::SECONDARY);
    });

    test('cannot set outer property directly', function() {
        $instance = new BackgroundCollection();

        expect(fn() => $instance->outer = ThemeColor::PRIMARY)->toThrow(\Error::class);
    });

    test('cannot set inner property directly', function() {
        $instance = new BackgroundCollection();

        expect(fn() => $instance->inner = ThemeColor::PRIMARY)->toThrow(\Error::class);
    });
});

describe('creation from a range of formats using transform_to_collection', function() {
    it('creates a BackgroundCollection object from a pair of colours', function($input) {
        $collection = BackgroundCollection::transform_to_collection($input);

        expect($collection)->toBeInstanceOf(BackgroundCollection::class);
    })->with('colour pairs');

    it('sets the correct values from a pair of colours', function($input) {
        $collection = BackgroundCollection::transform_to_collection($input);

        expect($collection->outer)->toBe(ThemeColor::DARK)
            ->and($collection->inner)->toBe(ThemeColor::LIGHT);
    })->with('colour pairs');

    it('creates a BackgroundCollection object from a gradient + a colour', function($input) {
        $collection = BackgroundCollection::transform_to_collection($input);

        expect($collection)->toBeInstanceOf(BackgroundCollection::class);
    })->with('gradient + colour');

    it('sets the correct values from a gradient + a colour', function($input) {
        $collection = BackgroundCollection::transform_to_collection($input);

        expect($collection->outer->value)->toBe('dark-light')
            ->and($collection->inner)->toBe(ThemeColor::PRIMARY);
    })->with('gradient + colour');

    it('returns null for both values if invalid input is provided for a single value', function($input) {
        $collection = BackgroundCollection::transform_to_collection($input);

        expect($collection->outer)->toBeNull()
            ->and($collection->inner)->toBeNull();
    })->with('invalid inputs');

    it('returns a valid outer value + null inner value if a valid outer value and invalid inner value are provided', function() {
        $collection = BackgroundCollection::transform_to_collection(['outer' => 'primary', 'inner' => '#000']);

        expect($collection->outer)->toBe(ThemeColor::PRIMARY)
            ->and($collection->inner)->toBeNull();
    });
});

dataset('colour pairs', [
    'BackgroundCollection object'                                 => new BackgroundCollection(ThemeColor::DARK, ThemeColor::LIGHT),
    'indexed array of colours'                                    => [['dark', 'light']],
    'associative array of colours in order (strings)'             => [['outer' => 'dark', 'inner' => 'light']],
    'associative array of colours in order (objects)'             => [['outer' => ThemeColor::DARK, 'inner' => ThemeColor::LIGHT]],
    'associative array of colours out of order (strings)'         => [['inner' => 'light', 'outer' => 'dark']],
    'associative array of colours out of order (objects)'         => [['inner' => ThemeColor::LIGHT, 'outer' => ThemeColor::DARK]],
]);

dataset('gradient + colour', [
    'BackgroundCollection object' 		                    => new BackgroundCollection(new ThemeGradient('dark', 'light'), ThemeColor::PRIMARY),
    'indexed array with a gradient string'              => [['dark-light', 'primary']],
    'indexed array with a gradient object'              => [[new ThemeGradient('dark', 'light'), ThemeColor::PRIMARY]],
]);

dataset('invalid inputs', [
    'invalid string'                    => 'not-a-valid-colour',
    'integer'                           => 123,
    'object of wrong type'              => (object)['color' => 'invalid'],
    'array of wrong format'             => [['outer' => 'invalid']],
]);
