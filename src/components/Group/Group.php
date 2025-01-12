<?php
namespace Doubleedesign\Comet\Components;

class Group extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Group.group');
	}
}
