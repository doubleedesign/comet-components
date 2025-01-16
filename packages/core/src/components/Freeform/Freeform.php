<?php
namespace Doubleedesign\Comet\Core;

class Freeform extends Renderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Freeform.freeform');
	}
}
