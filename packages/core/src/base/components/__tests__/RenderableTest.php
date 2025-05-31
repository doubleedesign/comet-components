<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\{Renderable};
use Doubleedesign\Comet\TestUtils\PestUtils;

/**
 * Function to create a generic component class that extends the abstract class being tested,
 * allowing it to stay local to this test/file
 */
function create_renderable(array $attributes = [], string $bladeFile = 'components.test-component'): Renderable {
    return new class($attributes, $bladeFile) extends Renderable {
        public function render(): void {
            // TODO: Implement render() method.
        }
    };
}

describe('Renderable', function() {
    describe('Constructor', function() {

        it('handles className string as classes', function() {
            $attributes = ['className' => 'class1 class2 class3'];
            $component = create_renderable($attributes, 'components.test');

            $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
            expect($classes)->toContain('class1', 'class2', 'class3');
        });

        it('handles classes array', function() {
            $attributes = ['classes' => ['array-class1', 'array-class2']];
            $component = create_renderable($attributes, 'components.test');

            $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
            expect($classes)->toContain('array-class1', 'array-class2');
        });

        it('merges className and classes arrays', function() {
            $attributes = [
                'className' => 'string-class1 string-class2',
                'classes'   => ['array-class1', 'array-class2']
            ];
            $component = create_renderable($attributes, 'components.test');

            $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
            expect($classes)->toContain('string-class1', 'string-class2', 'array-class1', 'array-class2');
        });

        it('handles style array', function() {
            $attributes = [
                'style' => [
                    'color'            => 'red',
                    'background-color' => 'blue'
                ]
            ];
            $component = create_renderable($attributes, 'components.test');

            $styles = PestUtils::call_protected_method($component, 'get_inline_styles');
            expect($styles)->toBe(['color' => 'red', 'background-color' => 'blue']);
        });

        it('sets testId when provided', function() {
            $attributes = ['testId' => 'my-test-id'];
            $component = create_renderable($attributes, 'components.test');

            $htmlAttributes = PestUtils::call_protected_method($component, 'get_html_attributes');
            expect($htmlAttributes['data-testid'])->toBe('my-test-id');
        });
    });

    describe('BEM name', function() {
        it('uses the shortName by default', function() {
            $component = create_renderable([], 'components.button');

            $bemName = PestUtils::call_protected_method($component, 'get_bem_name');
            expect($bemName)->toBe('button');
        });

        it('prefixes with context if provided', function() {
            $component = create_renderable(['context' => 'card'], 'components.button');

            $bemName = PestUtils::call_protected_method($component, 'get_bem_name');
            expect($bemName)->toBe('card__button');
        });

        it('de-duplicates context and start of blade file being the same word', function() {
            $component = create_renderable(['context' => 'navigation'], 'components.navigation-item');

            $bemName = PestUtils::call_protected_method($component, 'get_bem_name');
            expect($bemName)->toBe('navigation__item');
        });

        it('can update context after creation', function() {
            $component = create_renderable([], 'components.button');

            PestUtils::call_protected_method($component, 'set_context', ['card-header']);

            $bemName = PestUtils::call_protected_method($component, 'get_bem_name');
            expect($bemName)->toBe('card-header__button');
        });
    });

    describe('Class filtering', function() {
        it('includes BEM name in filtered classes', function() {
            $component = create_renderable([], 'components.test-component');

            $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
            expect($classes)->toContain('test-component');
        });

        it('filters out redundant WordPress classes', function() {
            $attributes = [
                'classes' => [
                    'custom-class',
                    'is-style-default',
                    'is-stacked-on-mobile',
                    'wp-elements-button'
                ]
            ];
            $component = create_renderable($attributes, 'components.test');

            $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
            expect($classes)->toContain('custom-class')
                ->and($classes)->not->toContain('is-style-default', 'is-stacked-on-mobile')
                ->and($classes)->not->toContain('wp-elements-button');
        });

        it('removes duplicate classes', function() {
            $attributes = [
                'className' => 'duplicate-class',
                'classes'   => ['duplicate-class', 'unique-class']
            ];
            $component = create_renderable($attributes, 'components.test');

            $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
            $duplicateCount = count(array_filter($classes, fn($class) => $class === 'duplicate-class'));
            expect($duplicateCount)->toBe(1);
        });
    });

    describe('Other HTML attributes', function() {
        it('includes id attribute', function() {
            $attributes = ['id' => 'test-id'];
            $component = create_renderable($attributes, 'components.test');

            $htmlAttributes = PestUtils::call_protected_method($component, 'get_html_attributes');
            expect($htmlAttributes['id'])->toBe('test-id');
        });

        it('builds style attribute from array', function() {
            $attributes = [
                'style' => [
                    'color'     => 'red',
                    'font-size' => '16px'
                ]
            ];
            $component = create_renderable($attributes, 'components.test');

            $htmlAttributes = PestUtils::call_protected_method($component, 'get_html_attributes');

            expect($htmlAttributes['style'])->toContain('color:red')
                ->and($htmlAttributes['style'])->toContain('font-size:16px');
        });

        it('includes data attributes', function() {
            $attributes = [
                'data-custom'  => 'custom-value',
                'data-another' => 'another-value'
            ];
            $component = create_renderable($attributes, 'components.test');

            $htmlAttributes = PestUtils::call_protected_method($component, 'get_html_attributes');

            expect($htmlAttributes['data-custom'])->toBe('custom-value')
                ->and($htmlAttributes['data-another'])->toBe('another-value');
        });

        it('filters out empty attributes', function() {
            $attributes = [
                'id'         => '',
                'data-empty' => null,
                'data-valid' => 'value'
            ];
            $component = create_renderable($attributes, 'components.test');

            $htmlAttributes = PestUtils::call_protected_method($component, 'get_html_attributes');

            expect($htmlAttributes)->not->toHaveKey('id')
                ->and($htmlAttributes)->not->toHaveKey('data-empty')
                ->and($htmlAttributes)->toHaveKey('data-valid');
        });

        it('accepts data attributes from traits', function() {
            $component = create_renderable([], 'components.test');
            PestUtils::call_protected_method($component, 'add_attributes', [
                ['data-test'    => 'test-value']
            ]);

            $attributes = PestUtils::call_protected_method($component, 'get_html_attributes');
            expect($attributes['data-test'])->toBe('test-value');
        });
    });
});
