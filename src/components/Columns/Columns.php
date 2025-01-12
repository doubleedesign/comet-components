<?php
namespace Doubleedesign\Comet\Components;

class Columns extends LayoutComponent {

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Columns.columns');
	}
}
