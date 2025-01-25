<?php
namespace Doubleedesign\Comet\Core;

class ComplexComponent extends UIComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.ThisComponent.this-component');
    }

    function get_inline_styles(): array {
        return [];
    }

	#[NotImplemented]
	function render(): void {
		// Check the render method of the parent and see if it needs to be overridden,
		// if not then remove this method and the annotation
	}
}
