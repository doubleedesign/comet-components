<?php
namespace Doubleedesign\Comet\Core;

class Buttons extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Buttons.buttons');
	}

	function get_inline_styles(): array {
		// TODO: Implement get_inline_styles() method.
		return [];
	}

	function render(): void {
		// TODO: Implement render() method.
	}
}
