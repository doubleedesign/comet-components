<?php
namespace Doubleedesign\Comet\Core;

class WrapperComponent extends LayoutComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Component.this-component');
    }

	#[NotImplemented]
	function render(): void {
		// Check the render method of the parent and see if it needs to be overridden,
		// if not then remove this method and the annotation
	}
}
