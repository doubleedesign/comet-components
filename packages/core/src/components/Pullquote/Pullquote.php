<?php
namespace Doubleedesign\Comet\Core;

class Pullquote extends TextElementExtended {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Pullquote.pullquote');
	}

	#[NotImplemented]
	function render(): void {
	}
}
