<?php
use Doubleedesign\Comet\Core\{AccordionPanel, Config};

beforeEach(function() {
    Config::init();
});

/**
 * Note: This component's Blade template renders its children directly
 * and the wrapping HTML is handled by the parent + Vue,
 * so we can't assert the full rendered HTML here - but we can check raw method results.
 */
describe('AccordionPanel', function() {

    test('default BEM class structure', function() {
        $component = new AccordionPanel([], []);
        $classes = $component->get_filtered_classes();

        expect($classes)->toMatchArray(['accordion__panel']);
    });

    test('BEM class structure with custom context', function() {
        $component = new AccordionPanel(['context' => 'custom'], []);
        $classes = $component->get_filtered_classes();

        expect($classes)->toMatchArray(['custom__accordion__panel']);
    });

    test('BEM class structure with custom modifier', function() {
        $component = new AccordionPanel([], []);
        $component->set_bem_modifier('large');
        $classes = $component->get_filtered_classes();

        expect($classes)->toMatchArray(['accordion__panel', 'accordion__panel--large']);
    });

    test('BEM class structure with custom context and modifier', function() {
        $component = new AccordionPanel(['context' => 'custom'], []);
        $component->set_bem_modifier('large');
        $classes = $component->get_filtered_classes();

        expect($classes)->toMatchArray(['custom__accordion__panel', 'custom__accordion__panel--large']);
    });
});
