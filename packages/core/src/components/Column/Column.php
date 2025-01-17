<?php
namespace Doubleedesign\Comet\Core;

class Column extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Column.column');
	}

	#[NotImplemented]
	function render(): void {
	}
}
