<?php
namespace Doubleedesign\Comet\Components;

class ListItem extends UIComponent implements Renderable {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, '', $innerComponents);
	}
}
