<?php

use Doubleedesign\Comet\Core\{BackgroundCollection, Config, BackgroundColor, ThemeColor, ThemeGradient};
use function Patchwork\{redefine, relay, restoreAll};
use function Spies\{make_spy, match_array, expect_spy};

beforeEach(function() {
    Config::init();
    Config::getInstance()->set_global_background(ThemeColor::WHITE);
});

afterEach(function() {
    restoreAll();
});

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  array  $attributes
 *
 * @return object
 */
function create_component_with_bg_color(array $attributes): object {
    return new class($attributes) {
        use BackgroundColor;

        public function __construct(array $attributes) {
            $this->set_background_colors($attributes);
        }
    };
}

/**
 * Function to create a component class that uses the trait and can handle inner components
 *
 * @param  array  $attributes
 * @param  array  $innerComponents
 *
 * @return object
 */
function create_component_with_inner_components(array $attributes = [], array $innerComponents = []): object {
    return new class($attributes, $innerComponents) {
        use BackgroundColor;
        public array $innerComponents = [];

        public function __construct(array $attributes, array $innerComponents = []) {
            $this->set_background_colors($attributes);
            $this->innerComponents = $innerComponents;
        }
    };
}

// Ensure backwards compatibility with the old attribute name is maintained
dataset('all valid attribute names', [['backgroundColor'], ['backgroundColors']]);

dataset('all valid attribute types (single colour)', [
    'ThemeColor object'                 => ThemeColor::PRIMARY,
    'ThemeColor string'                 => 'primary',
    'Array with single object'          => [ThemeColor::PRIMARY],
    'Array with single string'          => ['primary'],
]);

dataset('all valid attribute types (single gradient)', [
    'ThemeGradient object'              => new ThemeGradient('dark', 'light'),
    'ThemeGradient string'              => 'dark-light',
    'Array with single object'          => [new ThemeGradient('dark', 'light')],
    'Array with single string'          => ['dark-light'],
]);

describe('Set value from attributes', function() {
    it('sets a valid single colour', function(string $attributeName, $value) {
        $component = create_component_with_bg_color([$attributeName => $value]);

        expect($component->get_background_color())->toEqual(ThemeColor::PRIMARY)
            ->and($component->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY);
    })->with('all valid attribute names')
        ->with('all valid attribute types (single colour)');

    it('sets a valid single gradient', function(string $attributeName, $value) {
        $component = create_component_with_bg_color([$attributeName => $value]);

        expect($component->get_background_color())->toEqual(new ThemeGradient('dark', 'light'))
            ->and($component->get_background_colors()->outer)->toEqual(new ThemeGradient('dark', 'light'));
    })->with('all valid attribute names')
        ->with('all valid attribute types (single gradient)');

    it('sets a valid colour pair', function() {
        $spy = make_spy();
        redefine('Doubleedesign\Comet\Core\BackgroundCollection::transform_to_collection', function($value) use ($spy) {
            $spy($value);

            return relay();
        });

        $component = create_component_with_bg_color(['backgroundColors' => ['light', 'dark']]);

        expect_spy($spy)->to_have_been_called->with(match_array(['light', 'dark']))->verify();
        expect($component->get_background_colors())->toBeInstanceOf(BackgroundCollection::class);
    })->with('all valid attribute names');
});

describe('Set value from component defaults', function() {
    it('sets a valid single colour', function(string $attributeName, $value) {
        redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => [$attributeName => $value]);

        $component = create_component_with_bg_color([]);

        expect($component->get_background_color())->toEqual(ThemeColor::PRIMARY);
        expect($component->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY);
    })->with('all valid attribute names')
        ->with('all valid attribute types (single colour)');

    it('sets a valid single gradient', function(string $attributeName, $value) {
        redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => [$attributeName => $value]);

        $component = create_component_with_bg_color([]);

        expect($component->get_background_color())->toEqual(new ThemeGradient('dark', 'light'));
        expect($component->get_background_colors()->outer)->toEqual(new ThemeGradient('dark', 'light'));
    })->with('all valid attribute names')
        ->with('all valid attribute types (single gradient)');

    it('sets a valid colour pair', function(string $attributeName) {
        redefine('Doubleedesign\Comet\Core\Config::get_component_defaults', fn() => ['backgroundColors' => ['light', 'dark']]);
        $spy = make_spy();
        redefine('Doubleedesign\Comet\Core\BackgroundCollection::transform_to_collection', function($value) use ($spy) {
            $spy($value);

            return relay();
        });

        $component = create_component_with_bg_color(['backgroundColors' => ['light', 'dark']]);

        expect_spy($spy)->to_have_been_called->with(match_array(['light', 'dark']))->verify();
        expect($component->get_background_colors())->toBeInstanceOf(BackgroundCollection::class);
    })->with('all valid attribute names');
});

describe('Remove redundant background colours from direct inner components', function() {

    it('removes inner backgrounds when all backgrounds match the component', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
        $child2 = create_component_with_bg_color(['backgroundColor' => 'primary']);
        $child3 = create_component_with_bg_color(['backgroundColor' => 'primary']);

        $parent = create_component_with_inner_components(
            ['backgroundColor' => 'primary'],
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY);
        foreach ($parent->innerComponents as $child) {
            expect($child->get_background_color())->toBeNull();
        }
    });

    it('removes only the same background from inner components when they are mixed', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
        $child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child3 = create_component_with_bg_color(['backgroundColor' => 'accent']);

        $parent = create_component_with_inner_components(
            ['backgroundColor' => 'primary'],
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY)
            ->and($parent->innerComponents[0]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[1]->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[2]->get_background_color())->toEqual(ThemeColor::ACCENT);
    });

    it('does nothing if there is only one inner component', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);

        $parent = create_component_with_inner_components(
            ['backgroundColor' => 'primary'],
            [$child1]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_color())->toEqual(ThemeColor::PRIMARY)
            ->and($parent->innerComponents[0]->get_background_color())->toEqual(ThemeColor::PRIMARY);
    });
});

describe('Set background colour based on inner components', function() {
    it('sets a background when all inner components have the same background and the parent does not have one set', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child3 = create_component_with_bg_color(['backgroundColor' => 'secondary']);

        $parent = create_component_with_inner_components(
            [], // No background
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[0]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[1]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[2]->get_background_color())->toBeNull();
    });

    it('does not set a background when the parent already has an outer background set', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child3 = create_component_with_bg_color(['backgroundColor' => 'secondary']);

        $parent = create_component_with_inner_components(
            ['backgroundColor' => 'primary'],
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toEqual(ThemeColor::PRIMARY)
            ->and($parent->innerComponents[0]->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[1]->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[2]->get_background_color())->toEqual(ThemeColor::SECONDARY);
    });

    it("does not set a background when the children's background matches the parent's outer background but not inner background", function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child3 = create_component_with_bg_color(['backgroundColor' => 'secondary']);

        $parent = create_component_with_inner_components(
            ['backgroundColors' => ['secondary', 'primary']],
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toEqual(ThemeColor::SECONDARY)
            ->and($parent->get_background_colors()->inner)->toEqual(ThemeColor::PRIMARY)
            ->and($parent->innerComponents[0]->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[1]->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[2]->get_background_color())->toEqual(ThemeColor::SECONDARY);
    });

    it('does nothing when children have different backgrounds', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
        $child2 = create_component_with_bg_color(['backgroundColor' => 'secondary']);
        $child3 = create_component_with_bg_color(['backgroundColor' => 'accent']);

        $parent = create_component_with_inner_components(
            [], // No background
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toBeNull()
            ->and($parent->get_background_colors()->inner)->toBeNull()
            ->and($parent->innerComponents[0]->get_background_color())->toEqual(ThemeColor::PRIMARY)
            ->and($parent->innerComponents[1]->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[2]->get_background_color())->toEqual(ThemeColor::ACCENT);
    });

    it('sets a background when the inner components have a mix of the same and no backgrounds set', function() {
        $child1 = create_component_with_bg_color(['backgroundColor' => 'primary']);
        $child2 = create_component_with_bg_color([]); // Null background
        $child3 = create_component_with_bg_color(['backgroundColor' => 'primary']);

        $parent = create_component_with_inner_components(
            [],
            [$child1, $child2, $child3]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors()->outer)->toBe(ThemeColor::PRIMARY)
            ->and($parent->innerComponents[0]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[1]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[2]->get_background_color())->toBeNull();
    });

    it('does nothing if there is only one inner component', function() {
        $child = create_component_with_bg_color(['backgroundColor' => 'primary']);
        $parent = create_component_with_inner_components(
            [], // No background
            [$child]
        );

        $parent->simplify_all_background_colors();

        expect($parent->get_background_colors())->toBeNull()
            ->and($parent->innerComponents[0]->get_background_color())->toEqual(ThemeColor::PRIMARY);
    });
});
