<?php
namespace Doubleedesign\Comet\Core;

class Freeform extends Renderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Freeform.freeform');
	}

	function get_inline_styles(): array {
		return [];
	}

	#[NotImplemented]
	function render(): void {
	}
}
