<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::DIV)]
class Columns extends LayoutComponent {
	private int $qty;

	/**
	 * @var bool $allowStacking
	 * @description Whether to adapt the layout by stacking columns when the viewport or container is narrow
	 */
	protected bool $allowStacking = true;

	/**
	 * @var array<Column> $innerComponents
	 * @description Inner column components
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		$this->qty = count($innerComponents);
		$this->allowStacking = $attributes['allowStacking'] ?? $attributes['isStackedOnMobile'] ?? true;

		$columnWidths = array_map(function($column) {
			return $column->get_width();
		}, $innerComponents);
		// If all column widths are the same, remove them so unnecessary inline styles are not included in the final HTML
		if(count(array_unique($columnWidths)) === 1) {
			$updatedInnerComponents = [];
			foreach($innerComponents as $column) {
				$column->set_width(null);
				$updatedInnerComponents[] = $column;
			}
		}

		parent::__construct($attributes, $updatedInnerComponents ?? $innerComponents, 'components.Columns.columns');
	}

	function get_filtered_classes(): array {
		return array_merge(
			parent::get_filtered_classes(),
			["columns--$this->qty"]
		);
	}

	function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if($this->allowStacking) {
			$attributes['data-allow-layout-stacking'] = 'true';
		}

		return $attributes;
	}
}
