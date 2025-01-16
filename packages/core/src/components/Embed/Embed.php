<?php
namespace Doubleedesign\Comet\Core;

class Embed extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Embed.embed');
	}
}
