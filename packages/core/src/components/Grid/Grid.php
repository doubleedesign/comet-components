<?php
namespace Doubleedesign\Comet\Core;

class Grid extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Component.grid');
	}

	#[NotImplemented]
	function render(): void {
	}
}
