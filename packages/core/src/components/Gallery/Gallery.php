<?php
namespace Doubleedesign\Comet\Core;

class Gallery extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Gallery.gallery');
	}

	#[NotImplemented]
	function render(): void {
	}
}
