<?php
namespace Doubleedesign\Comet\Components;

class ComplexComponent extends UIComponent {
    function __construct(array $attributes, array $innerComponents) {
        $bladeFile = 'components.ComplexComponent.complex-component';
        parent::__construct($attributes, $innerComponents, $bladeFile);
    }

    function get_inline_styles(): array {
        // TODO: Implement get_inline_styles() method.
        return [];
    }

    function render(): void {
        // TODO: Implement render() method.
    }
}
