<?php
namespace Doubleedesign\Comet\Core;

class ComplexComponent extends UIComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.ThisComponent.this-component');
    }

    function get_inline_styles(): array {
        // TODO: Implement get_inline_styles() method.
        return [];
    }

	#[NotImplemented]
    function render(): void {
        // TODO: Implement render() method.
    }
}
