<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\UIComponent;
use Doubleedesign\Comet\TestUtils\PestUtils;

/**
 * Function to create a generic component class that extends the abstract class being tested,
 * allowing it to stay local to this test/file
 */
function create_uicomponent(array $attributes = [], array $innerComponents = [], string $bladeFile = 'components.test-component'): UIComponent {
    return new class($attributes, $innerComponents, $bladeFile) extends UIComponent {
        public function render(): void {
            // TODO: Implement render() method.
        }
    };
}

describe('UIComponent', function() {

    test('transforms WordPress class names into BEM modifiers', function() {
        $attributes = ['className' => 'is-style-example'];
        $component = create_uicomponent($attributes, [], 'components.test');

        $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
        expect($classes)->toContain('test--example');
    });

    test('transforms the classes from array to string as expected', function() {
        $attributes = ['classes' => ['class1', 'class2']];
        $component = create_uicomponent($attributes, [], 'components.test');

        $classes = PestUtils::call_protected_method($component, 'get_filtered_classes_string');
        expect($classes)->toBe('test class1 class2');
    });

});
