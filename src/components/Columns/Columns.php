<?php
namespace Doubleedesign\Comet\Components;

class Columns extends UIComponent implements Renderable {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, '', $innerComponents);
	}
}
