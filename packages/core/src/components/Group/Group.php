<?php
namespace Doubleedesign\Comet\Core;

class Group extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Group.group');
	}

	#[NotImplemented]
	function render(): void {
	}
}
