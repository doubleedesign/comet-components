<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Grid extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Component.grid');
	}

	#[NotImplemented]
	function render(): void {
	}
}
