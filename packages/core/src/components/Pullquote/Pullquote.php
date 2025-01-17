<?php
namespace Doubleedesign\Comet\Core;

class Pullquote extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Pullquote.pullquote');
	}

	#[NotImplemented]
	function render(): void {
	}
}
