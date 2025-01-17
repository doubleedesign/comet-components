<?php
namespace Doubleedesign\Comet\Core;

class Buttons extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Buttons.buttons');
	}

	#[NotImplemented]
	function render(): void {
	}
}
