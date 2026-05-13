<?php

use Doubleedesign\Comet\Core\BlockElementModifier;

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 *
 * @param  string  $bladeFile
 * @param  string|null  $context  - to test what happens when explicit context is set on the component before BEM is initialised
 * @param  string|null  $shortName
 *
 * @return object
 */
function create_component_with_bem_trait(string $bladeFile, ?string $context = null, ?string $shortName = ''): object {
    return new class($bladeFile, $context, $shortName) {
        use BlockElementModifier;
        protected array $classes = [];

        public function __construct($bladeFile, $context, $shortName) {
            $this->init_bem_structure($bladeFile, $context, $shortName);
        }
    };
}


describe('Component is the block (top-level)', function() {
    test('default result', function() {
        $component = create_component_with_bem_trait('components.Accordion.accordion');

        expect($component->get_bem_structure())->toEqual([
            'block'    => 'accordion',
            'element'  => null,
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toEqual(['accordion']);

    });

    test('with explicit context', function() {
        $component = create_component_with_bem_trait('components.Accordion.accordion', 'test-wrapper');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'test-wrapper',
            'element'  => 'accordion',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['test-wrapper__accordion']);

    });

    test('with explicit shortName', function() {
        $component = create_component_with_bem_trait('components.Accordion.accordion', null, 'custom-thing');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'custom-thing',
            'element'  => null,
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['custom-thing']);
    });

    test('it does not double up when explicit context and explicit shortName are the same', function() {
        $component = create_component_with_bem_trait('components.Group.group', 'custom', 'custom');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'custom',
            'element'  => null,
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['custom']);
    });

    test('it ignores empty explicit context string', function() {
        $component = create_component_with_bem_trait('components.Accordion.accordion', '');

        expect($component->get_bem_structure())->toEqual([
            'block'    => 'accordion',
            'element'  => null,
            'modifier' => null
        ]);
    });

    test('it ignores explicit context that matches the shortname', function() {
        $component = create_component_with_bem_trait('components.Accordion.accordion', 'accordion');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'accordion',
            'element'  => null,
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['accordion']);
    });

    test('it correctly appends a custom modifier', function() {
        $component = create_component_with_bem_trait('components.Image.image');
        $component->set_bem_modifier('basic');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'image',
            'element'  => null,
            'modifier' => 'basic'
        ])
            ->and($component->get_bem_classes())->toMatchArray(['image', 'image--basic']);
    });

    test('it handles component-level override of the block name', function() {
        $component = create_component_with_bem_trait('components.Image.image');
        // Simulate a component that overrides the block name
        $component->set_bem_block('photo');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'photo',
            'element'  => null,
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['photo']);
    });

});

describe('Component is the element (nested)', function() {

    test('default result (two-word component)', function() {
        $component = create_component_with_bem_trait('components.Card.CardBody.card-body');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'card',
            'element'  => 'body',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['card__body']);
    });

    test('default result (two-word parent)', function() {
        $component = create_component_with_bem_trait('components.FileGroup.File.file');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'file-group',
            'element'  => 'file',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['file-group__file']);
    });

    test('with explicit context', function() {
        $component = create_component_with_bem_trait('components.Table.TableCell.table-cell', 'profile');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'profile__table',
            'element'  => 'cell',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['profile__table__cell']);

    });

    test('with explicit shortName', function() {
        $component = create_component_with_bem_trait('components.Table.TableCell.table-cell', null, 'custom-thing');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'table',
            'element'  => 'custom-thing',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['table__custom-thing']);
    });

    test('with explicit context that repeats the component name per the blade file', function() {
        $component = create_component_with_bem_trait('components.Accordion.AccordionPanel.accordion-panel', 'accordion');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'accordion',
            'element'  => 'panel',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['accordion__panel']);
    });

    test('it correctly appends a custom modifier', function() {
        $component = create_component_with_bem_trait('components.Card.CardHeader.card-header', 'card');
        $component->set_bem_modifier('highlighted');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'card',
            'element'  => 'header',
            'modifier' => 'highlighted'
        ])
            ->and($component->get_bem_classes())->toMatchArray(['card__header', 'card__header--highlighted']);
    });

    test('it handles component-level override of the block name', function() {
        $component = create_component_with_bem_trait('components.Card.CardHeader.card-header', 'card');
        $component->set_bem_block('panel');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'panel',
            'element'  => 'header',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['panel__header']);
    });

    test('it handles component-level override of the element name', function() {
        $component = create_component_with_bem_trait('components.Card.CardHeader.card-header');
        // Simulate a component that overrides the element name
        $component->set_bem_element('title');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'card',
            'element'  => 'title',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['card__title']);
    });

    // Note: If this is something that actually happens in practice, it might indicate a problematic class design
    // except maybe for things that extend super generic components like Group
    test('it handles component-level override of both block and element names', function() {
        $component = create_component_with_bem_trait('components.Card.CardHeader.card-header', 'card');
        $component->set_bem_block('panel');
        $component->set_bem_element('title');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'panel',
            'element'  => 'title',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['panel__title']);
    });

    test('repeated words that are not at the start of component names should not be stripped', function() {
        // Menu is the repeated word here but submenu should be kept because Menu is not at the start
        $component = create_component_with_bem_trait('components.Menu.SubMenu.sub-menu');

        expect($component->get_bem_structure())->toMatchArray([
            'block'    => 'menu',
            'element'  => 'sub-menu',
            'modifier' => null
        ])
            ->and($component->get_bem_classes())->toMatchArray(['menu__sub-menu']);
    });
});

describe('Component is the element (deeply nested)', function() {

    describe('where each level is prefixed by the preceding level component name', function() {

        test('repetition is stripped', function() {
            $component = create_component_with_bem_trait('components.Menu.MenuList.MenuListItem.menu-list-item');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'menu__list',
                'element'  => 'item',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['menu__list__item']);

        });

        test('explicit context is prepended', function() {
            $component = create_component_with_bem_trait('components.Menu.MenuList.MenuListItem.menu-list-item', 'site-header');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'site-header__menu__list',
                'element'  => 'item',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['site-header__menu__list__item']);

        });
    });

    describe('where every level if prefixed by the top-level component name only', function() {

        test('repetition should be stripped', function() {
            $component = create_component_with_bem_trait('components.Card.CardContent.CardImage.card-image');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'card__content',
                'element'  => 'image',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['card__content__image']);
        });

        test('explicit context is prepended', function() {
            $component = create_component_with_bem_trait('components.Card.CardContent.CardImage.card-image', 'profile');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'profile__card__content',
                'element'  => 'image',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['profile__card__content__image']);

        });
    });

    describe('where the end of the block name matches the start of the element name', function() {

        test('kebab-cased words at the start of the element that match the end of the block should be stripped', function() {
            $component = create_component_with_bem_trait('components.Menu.SubMenu.SubMenuItem.sub-menu-item');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'menu__sub-menu',
                'element'  => 'item',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['menu__sub-menu__item']);
        });

        test('if the element is a single word that matches the end of the block, it should not be made empty', function() {
            $component = create_component_with_bem_trait('components.Menu.SubMenu.SubMenuItem.item');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'menu__sub-menu',
                'element'  => 'item',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['menu__sub-menu__item']);
        });

        test('explicit context is prepended', function() {
            $component = create_component_with_bem_trait('components.Menu.SubMenu.SubMenuItem.sub-menu-item', 'site-header');

            expect($component->get_bem_structure())->toMatchArray([
                'block'    => 'site-header__menu__sub-menu',
                'element'  => 'item',
                'modifier' => null
            ])
                ->and($component->get_bem_classes())->toMatchArray(['site-header__menu__sub-menu__item']);

        });
    });
});

describe('Context updates after construction', function() {
    test('Basic component', function() {
        $component = create_component_with_bem_trait('components.Menu.menu');

        expect($component->get_bem_classes())->toMatchArray(['menu']);

        $component->update_context('site-header');

        expect($component->get_bem_classes())->toMatchArray(['site-header__menu']);
    });

    test('Basic nested component with shortname', function() {
        $component = create_component_with_bem_trait('components.Menu.MenuList.menu-list', null, 'sub-menu');

        expect($component->get_bem_classes())->toMatchArray(['menu__sub-menu']);

        $component->update_context('site-header');

        expect($component->get_bem_classes())->toMatchArray(['site-header__menu__sub-menu']);
    });
});
