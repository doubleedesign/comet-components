<?php
namespace Doubleedesign\Comet\Components;

class Gallery extends UIComponent implements Renderable {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, '', $innerComponents);
	}
}
