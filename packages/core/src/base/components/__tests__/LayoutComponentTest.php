<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\LayoutComponent;
use Doubleedesign\Comet\TestUtils\PestUtils;

/**
 * Function to create a generic component class that extends the abstract class being tested,
 * allowing it to stay local to this test/file
 */
function create_layout_component(array $attributes = [], array $innerComponents = [], string $bladeFile = 'components.test-component'): LayoutComponent {
    return new class($attributes, $innerComponents, $bladeFile) extends LayoutComponent {
        public function render(): void {
            // TODO: Implement render() method.
        }
    };
}

describe('LayoutComponent', function() {

    it('adds the "layout-block" class to the filtered classes', function() {
        $component = create_layout_component([], [], 'components.test');

        $classes = PestUtils::call_protected_method($component, 'get_filtered_classes');
        expect($classes)->toContain('layout-block');
    });
});
