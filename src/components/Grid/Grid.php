<?php
namespace Doubleedesign\Comet\Components;

class Grid extends UIComponent implements IRenderable {
	function __construct(array $attributes, string $content) {
		$this->attributes = $attributes;
		parent::__construct($this->attributes, $content);
	}
	
	function render(): void {
		parent::render();
	}
}
