<?php
namespace Doubleedesign\Comet\Core;

class Cover extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Cover.cover');
	}

	#[NotImplemented]
	function render(): void {
	}
}
