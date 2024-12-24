<?php
namespace Doubleedesign\Comet\Components;

class Column extends UIComponent implements IRenderable {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, '', $innerComponents);
	}
}
