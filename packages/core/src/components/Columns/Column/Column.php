<?php
namespace Doubleedesign\Comet\Core;

class Column extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Columns.Column.column');
		$this->shortName = 'columns__column';
	}
}
