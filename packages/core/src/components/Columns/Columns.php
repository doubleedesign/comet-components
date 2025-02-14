<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::DIV)]
class Columns extends LayoutComponent {
	private int $qty;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Columns.columns');
		$this->qty = count($innerComponents);
	}

	function get_filtered_classes(): array {
		return array_merge(
			parent::get_filtered_classes(),
			["columns--$this->qty"]
		);
	}
}
