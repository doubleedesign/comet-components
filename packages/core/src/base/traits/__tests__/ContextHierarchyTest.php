<?php
use Doubleedesign\Comet\Core\ContextHierarchy;

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  string  $bladeFile
 * @param  string|null  $explicit_context
 *
 * @return object
 */
function create_component_with_context_hierarchy_trait(string $bladeFile, ?string $explicit_context = null): object {
    return new class($bladeFile, $explicit_context) {
        use ContextHierarchy;
        public ?string $test_context = null;

        public function __construct($bladeFile, $explicit_context) {
            $this->test_context = $this->init_context($bladeFile)
                ->with_explicit_context($explicit_context)
                ->get_context();
        }
    };
}

test('top-level component does not have context if not explicitly provided', function() {
    $component = create_component_with_context_hierarchy_trait('components.Button.button');
    expect($component->test_context)->toBeNull();
});

test('top level component uses explicit context if provided', function() {
    $component = create_component_with_context_hierarchy_trait('components.Button.button', 'custom-context');
    expect($component->test_context)->toEqual('custom-context');
});

test('top-level component ignores explicit context that matches its own name', function() {
    $component = create_component_with_context_hierarchy_trait('components.Button.button', 'button');
    expect($component->test_context)->toBeNull();
});

test('nested component uses its parent as default context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel');
    expect($component->test_context)->toEqual('accordion');
});

test('nested component with explicit context prepends it to its chain', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel', 'custom');
    expect($component->test_context)->toEqual('custom__accordion');
});

test('nested component does not double up if explicit context matches its parent\'s name', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel', 'accordion');
    expect($component->test_context)->toEqual('accordion');
});

test('nested component has a chain of ancestor context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Card.CardContent.CardImage.card-image');
    expect($component->test_context)->toEqual('card__content');
});

test('deeply nested component with explicit context prepends it to its chain', function() {
    $component = create_component_with_context_hierarchy_trait('components.Card.CardContent.CardImage.card-image', 'custom');
    expect($component->test_context)->toEqual('custom__card__content');
});

test('deeply nested component with the same word in a different position to its parent does not strip it', function() {
    $component = create_component_with_context_hierarchy_trait('components.Menu.SubMenu.SubMenuItem.sub-menu-item');
    expect($component->test_context)->toEqual('menu__sub-menu');
});

test('deeply nested component avoids duplication in its chain of context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.PanelContent.panel-content');
    expect($component->test_context)->toEqual('accordion__panel');
});

test('with_explicit_context does not add anything if explicit context is null', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel', null);
    expect($component->test_context)->toEqual('accordion');
});
