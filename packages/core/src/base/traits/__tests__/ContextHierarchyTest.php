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
        public ?string $context = null;

        public function __construct($bladeFile, $explicit_context) {
            if (empty($explicit_context)) {
                $this->context = $this->init_context_from_blade_file($bladeFile)->get_basic_context();
            }
            else {
                $this->context = $this->init_context_from_blade_file($bladeFile)->with_explicit_context($explicit_context);
            }
        }
    };
}

test('top-level component does not have context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Button.button');
    expect($component->context)->toBeNull();
});

test('top level component with explicit context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Button.button');
    $component->context = 'custom-context';
    expect($component->context)->toEqual('custom-context');
});

test('top-level component ignores explicit context that matches its own name', function() {
    $component = create_component_with_context_hierarchy_trait('components.Button.button', 'button');
    \Symfony\Component\VarDumper\VarDumper::dump($component);
    expect($component->context)->toBeNull();
});

test('nested component uses its parent as default context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel');
    expect($component->context)->toEqual('accordion');
});

test('nested component with explicit context prepends it to its chain', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel', 'custom');
    expect($component->context)->toEqual('custom__accordion');
});

test('nested component ignores explicit context that matches its parent', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.accordion-panel', 'accordion');
    expect($component->context)->toEqual('accordion');
});

test('nested component has a chain of ancestor context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Card.CardContent.CardImage.card-image');
    expect($component->context)->toEqual('card__content');
});

test('deeply nested component with explicit context prepends it to its chain', function() {
    $component = create_component_with_context_hierarchy_trait('components.Card.CardContent.CardImage.card-image', 'custom');
    expect($component->context)->toEqual('custom__card__content');
});

test('deeply nested component with the same word in a different position to its parent does not strip it', function() {
    $component = create_component_with_context_hierarchy_trait('components.Menu.SubMenu.SubMenuItem.sub-menu-item');
    expect($component->context)->toEqual('menu__sub-menu');
});

test('deeply nested component avoids duplication in its chain of context', function() {
    $component = create_component_with_context_hierarchy_trait('components.Accordion.AccordionPanel.PanelContent.panel-content');
    expect($component->context)->toEqual('accordion__panel');
});
