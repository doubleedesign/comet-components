<?php
namespace Doubleedesign\Comet\Core;

class Embed extends Renderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Embed.embed');
	}

	#[NotImplemented]
	function render(): void {
	}
}
