<?php
namespace Doubleedesign\Comet\Core;

class Group extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Group.group');
	}

	function get_inline_styles(): array {
		// TODO: Implement get_inline_styles() method.
		return [];
	}

	function render(): void {
		// TODO: Implement render() method.
	}
}
