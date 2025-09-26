<?php
use Doubleedesign\Comet\Core\Utils;

describe('array_diff_end', function() {

    test('if nothing matches, the entire main array is returned', function() {
        $main = ['monica', 'rachel', 'phoebe'];
        $compareTo = ['chandler', 'joey', 'ross'];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toMatchArray(['monica', 'rachel', 'phoebe']);
    });

    test('if the first two items match, the result is the main array without those (same length)', function() {
        $main = ['monica', 'rachel', 'phoebe'];
        $compareTo = ['monica', 'rachel', 'chandler'];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toMatchArray(['phoebe']);
    });

    test('if the first two items match, the result is the main array without those (main is longer)', function() {
        $main = ['monica', 'rachel', 'phoebe', 'joey', 'ross'];
        $compareTo = ['monica', 'rachel', 'chandler'];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toMatchArray(['phoebe', 'joey', 'ross']);
    });

    test('if the main array is shorter and matches the start of the comparison array, the result is an empty array', function() {
        $main = ['monica', 'rachel'];
        $compareTo = ['monica', 'rachel', 'chandler'];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toEqual([]);
    });

    test('if all items match, the result is an empty array', function() {
        $main = ['monica', 'rachel', 'phoebe'];
        $compareTo = ['monica', 'rachel', 'phoebe'];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toEqual([]);
    });

    test('if the compareTo array is empty, the result is an empty array', function() {
        $main = ['monica', 'rachel', 'phoebe'];
        $compareTo = [];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toMatchArray(['monica', 'rachel', 'phoebe']);
    });

    test('if the main array is empty, the result is an empty array', function() {
        $main = [];
        $compareTo = ['monica', 'rachel', 'phoebe'];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toEqual([]);
    });

    test('if both arrays are empty, the result is an empty array', function() {
        $main = [];
        $compareTo = [];
        $result = Utils::array_diff_end($main, $compareTo);

        expect($result)->toEqual([]);
    });
});
