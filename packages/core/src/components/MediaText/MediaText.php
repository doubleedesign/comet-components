<?php
namespace Doubleedesign\Comet\Core;

class MediaText extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.MediaText.media-text');
	}

	#[NotImplemented]
	function render(): void {
	}
}
