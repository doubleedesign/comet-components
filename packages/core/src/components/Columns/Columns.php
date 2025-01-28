<?php
namespace Doubleedesign\Comet\Core;

class Columns extends LayoutComponent {
	private int $qty;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV, Tag::SECTION, Tag::MAIN];
	}

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
