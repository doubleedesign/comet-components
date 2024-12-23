<?php
namespace Doubleedesign\Comet\Components;

class Paragraph extends UIComponent implements Renderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content);
	}
}
