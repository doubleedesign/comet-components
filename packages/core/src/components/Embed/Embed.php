<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Embed extends Renderable {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Embed.embed');
	}

	#[NotImplemented]
	function render(): void {
	}
}
