<?php
namespace Doubleedesign\Comet\Core;

class Columns extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Columns.columns');
	}
}
