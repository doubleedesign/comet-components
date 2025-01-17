<?php
namespace Doubleedesign\Comet\Core;

class SimpleComponent extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.ThisComponent.this-component');
    }

	#[NotImplemented]
	function render(): void {
	}
}
