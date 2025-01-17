<?php
namespace Doubleedesign\Comet\Core;

class WrapperComponent extends LayoutComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Component.this-component');
    }

	#[NotImplemented]
	function render(): void {
	}
}
