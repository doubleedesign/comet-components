<?php
use Doubleedesign\Comet\Core\Utils;

describe('kebab_case', function() {

    test('converts camelCase to kebab-case', function() {
        $input = 'purpleMonkeyDishwasher';
        $result = Utils::kebab_case($input);

        expect($result)->toBe('purple-monkey-dishwasher');
    });

    test('converts PascalCase to kebab-case', function() {
        $input = 'PurpleMonkeyDishwasher';
        $result = Utils::kebab_case($input);

        expect($result)->toBe('purple-monkey-dishwasher');
    });

    test('handles single word input', function() {
        $input = 'Purple';
        $result = Utils::kebab_case($input);

        expect($result)->toBe('purple');
    });

    test('handles empty string input', function() {
        $input = '';
        $result = Utils::kebab_case($input);

        expect($result)->toBe('');
    });

    test('handles strings with no uppercase letters', function() {
        $input = 'purplemonkeydishwasher';
        $result = Utils::kebab_case($input);

        expect($result)->toBe('purplemonkeydishwasher');
    });
});

describe('pascal_case', function() {
    test('converts kebab-case to PascalCase', function() {
        $input = 'purple-monkey-dishwasher';
        $result = Utils::pascal_case($input);

        expect($result)->toBe('PurpleMonkeyDishwasher');
    });

    test('handles single word input', function() {
        $input = 'purple';
        $result = Utils::pascal_case($input);

        expect($result)->toBe('Purple');
    });

    test('handles empty string input', function() {
        $input = '';
        $result = Utils::pascal_case($input);

        expect($result)->toBe('');
    });

    test('handles strings with no hyphens', function() {
        $input = 'purplemonkeydishwasher';
        $result = Utils::pascal_case($input);

        expect($result)->toBe('Purplemonkeydishwasher');
    });
});

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
