<?php
namespace Doubleedesign\Comet\Core;

class Button extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Button.button');
	}
}
