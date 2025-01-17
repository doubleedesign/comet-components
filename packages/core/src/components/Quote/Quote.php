<?php
namespace Doubleedesign\Comet\Core;

class Quote extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Quote.quote');
	}

	#[NotImplemented]
	function render(): void {
	}
}
