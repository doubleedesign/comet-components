<?php

use Doubleedesign\Comet\Core\{Config, BackgroundColor, ThemeColor};
use function Patchwork\{restoreAll};

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
            $this->set_background_color($attributes);
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
            $this->set_background_color($attributes);
            $this->innerComponents = $innerComponents;
        }
    };
}

describe('Set value from attributes', function() {
    it('sets a valid single colour from a ThemeColor', function() {
        $component = create_component_with_bg_color(['backgroundColor' => ThemeColor::PRIMARY]);

        expect($component->get_background_color())->toEqual(ThemeColor::PRIMARY);
    });

    it('sets a valid single colour from a string', function() {
        $component = create_component_with_bg_color(['backgroundColor' => 'primary']);

        expect($component->get_background_color())->toEqual(ThemeColor::PRIMARY);
    });
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

        expect($parent->get_background_color())->toEqual(ThemeColor::PRIMARY);
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

        expect($parent->get_background_color())->toEqual(ThemeColor::PRIMARY)
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

        expect($parent->get_background_color())->toEqual(ThemeColor::SECONDARY)
            ->and($parent->innerComponents[0]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[1]->get_background_color())->toBeNull()
            ->and($parent->innerComponents[2]->get_background_color())->toBeNull();
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

        expect($parent->get_background_color())->toBeNull()
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

        expect($parent->get_background_color())->toBe(ThemeColor::PRIMARY)
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

        expect($parent->get_background_color())->toBeNull()
            ->and($parent->innerComponents[0]->get_background_color())->toEqual(ThemeColor::PRIMARY);
    });
});
