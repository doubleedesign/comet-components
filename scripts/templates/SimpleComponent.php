<?php
namespace Doubleedesign\Comet\Core;

class SimpleComponent extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.ThisComponent.this-component');
    }

	#[NotImplemented]
	function render(): void {
		// Check the render method of the parent and see if it needs to be overridden,
		// if not then remove this method and the annotation
	}
}
