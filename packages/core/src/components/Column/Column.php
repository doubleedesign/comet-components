<?php
namespace Doubleedesign\Comet\Core;

class Column extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Column.column');
	}

	function get_inline_styles(): array {
		// TODO: Implement get_inline_styles() method.
		return [];
	}

	function render(): void {
		// TODO: Implement render() method.
	}
}
