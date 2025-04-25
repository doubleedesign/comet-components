<?php
namespace Doubleedesign\Comet\Core;

/**
 * ComplexComponent component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ComplexComponent extends UIComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ThisComponent.this-component');
	}

	#[NotImplemented]
	function render(): void {
		// Check the render method of the parent and see if it needs to be overridden,
		// if not then remove this method and the annotation
	}
}
