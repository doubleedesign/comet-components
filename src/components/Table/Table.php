<?php
namespace Doubleedesign\Comet\Components;

class Table extends UIComponent implements IRenderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content);
	}
}
