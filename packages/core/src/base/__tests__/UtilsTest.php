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

describe('array_unique_sequential', function() {

    it('returns an empty input array as-is', function() {
        $input = [];
        $result = Utils::array_unique_sequential($input);

        expect($result)->toEqual([]);
    });

    it('de-duplicates adjacent duplicates', function() {
        $result = Utils::array_unique_sequential(['menu', 'menu', 'list', 'item']);

        expect($result)->toEqual(['menu', 'list', 'item']);
    });

    it('de-duplicates adjacent duplicates while leaving later instances of the same phrase in place', function() {
        $result = Utils::array_unique_sequential(['menu', 'menu', 'list', 'item', 'menu']);

        expect($result)->toEqual(['menu', 'list', 'item', 'menu']);
    });

    it('does not remove non-adjacent duplicates', function() {
        $result = Utils::array_unique_sequential(['menu', 'list', 'menu', 'item']);

        expect($result)->toEqual(['menu', 'list', 'menu', 'item']);
    });

    it('ignores a partial match', function() {
        $result = Utils::array_unique_sequential(['menu', 'list', 'sub-menu', 'list', 'item']);

        expect($result)->toEqual(['menu', 'list', 'sub-menu', 'list', 'item']);
    });

    it('de-duplicates an array of arrays', function() {
        $input = [['menu', 'list'], ['menu', 'list'], ['item']];
        $result = Utils::array_unique_sequential($input);

        expect($result)->toEqual([['menu', 'list'], ['item']]);
    });

    it('ignores arrays where the duplicates are not in the same order', function() {
        $input = [['menu', 'list'], ['list', 'menu'], ['item']];
        $result = Utils::array_unique_sequential($input);

        expect($result)->toEqual([['menu', 'list'], ['list', 'menu'], ['item']]);
    });

    it('does not remove non-adjacent duplicate arrays', function() {
        $input = [['menu', 'list'], ['item'], ['menu', 'list']];
        $result = Utils::array_unique_sequential($input);

        expect($result)->toEqual([['menu', 'list'], ['item'], ['menu', 'list']]);
    });
});

describe('array_unique_sequential_chunks', function() {

    it('returns an empty input array as-is', function() {
        $input = [];
        $result = Utils::array_unique_sequential_chunks($input);

        expect($result)->toEqual([]);
    });

    it('de-duplicates adjacent sequences from the beginning of the array', function($input, $expected) {
        $result = Utils::array_unique_sequential_chunks($input);

        expect($result)->toEqual($expected);
    })
        ->with('pair duplicates')
        ->with('triplet duplicates');

    it('de-duplicates a sequence from the first item', function($input, $expected) {
        $result = Utils::array_unique_sequential_chunks($input);

        expect($result)->toEqual($expected);
    })
        ->with('pair duplicates (offset 1)')
        ->with('triplet duplicates (offset 1)');

    it('does not remove duplicates that are not adjacent', function($input, $expected) {
        $result = Utils::array_unique_sequential_chunks($input);

        expect($result)->toEqual($expected);
    })
        ->with('non-adjacent duplicates');
});

// These datasets are in the format of [input array, expected output array]
dataset('pair duplicates', [
    array(
        ['menu', 'list', 'menu', 'list', 'item'],
        ['menu', 'list', 'item']
    ),
    array(
        ['menu', 'list', 'menu', 'list', 'menu', 'list', 'item'],
        ['menu', 'list', 'item']
    ),
    array(
        ['section', 'group', 'section', 'group', 'item'],
        ['section', 'group', 'item']
    ),
    array(
        ['featured', 'group', 'section', 'group', 'item'],
        ['featured', 'group', 'section', 'group', 'item']
    ),
]);

dataset('pair duplicates (offset 1)', [
    array(
        ['site-header', 'menu', 'list', 'menu', 'list', 'item'],
        ['site-header', 'menu', 'list', 'item']
    ),
    array(
        ['featured', 'section', 'group', 'section', 'group', 'item'],
        ['featured', 'section', 'group', 'item']
    ),
]);

dataset('triplet duplicates', [
    array(['menu', 'list', 'item', 'menu', 'list', 'item'], ['menu', 'list', 'item']),
]);

dataset('triplet duplicates (offset 1)', [
    array(
		['site-header', 'menu', 'list', 'item', 'menu', 'list', 'item'],
	    ['site-header', 'menu', 'list', 'item']
    ),
	array(
		['featured', 'section', 'group', 'columns', 'section', 'group', 'columns', 'column'],
	    ['featured', 'section', 'group', 'columns', 'column']
	),
]);

dataset('non-adjacent duplicates', [
    array(['menu', 'list', 'item', 'sub', 'menu', 'menu', 'list', 'item'], ['menu', 'list', 'item', 'sub', 'menu', 'list', 'item']),
    array(['menu', 'list', 'item', 'sub-menu', 'list', 'item'], ['menu', 'list', 'item', 'sub-menu', 'list', 'item']),
    array(['site-header', 'menu', 'list', 'item', 'sub-menu', 'list', 'item'], ['site-header', 'menu', 'list', 'item', 'sub-menu', 'list', 'item']),
]);

dataset('no duplicates', [
    ['menu', 'list', 'item'],
    ['site-header', 'menu', 'list', 'item'],
    ['site-header', 'menu', 'sub-menu']
]);
