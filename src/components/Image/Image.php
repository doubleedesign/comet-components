<?php
namespace Doubleedesign\Comet\Components;

class Image extends UIComponent implements Renderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content);
	}
}
