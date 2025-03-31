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
		parent::__construct($attributes, $updatedInnerComponents ?? $innerComponents, 'components.Columns.columns');
		$this->qty = count($innerComponents);
		$this->allowStacking = $attributes['allowStacking'] ?? $attributes['isStackedOnMobile'] ?? true;

		// If all column widths are the same, remove them so unnecessary inline styles are not included in the final HTML
		$columnWidths = array_map(function($column) {
			return $column->get_width();
		}, $innerComponents);
		if(count(array_unique($columnWidths)) === 1) {
			$updatedInnerComponents = [];
			foreach($innerComponents as $column) {
				$column->set_width(null);
				$updatedInnerComponents[] = $column;
			}
		}

		// If all column backgrounds are the same as this component's background, remove them for HTML and styling simplicity
		if(isset($this->backgroundColor)) {
			// Note: array_unique can't be run directly on the ThemeColors because enums aren't directly comparable,
			// so we need to use the string value after filtering out null values
			$columnsWithBackgrounds = array_filter($innerComponents, fn($column) => $column->get_background_color() !== null);
			$columnBackgrounds = array_unique(array_map(fn($column) => $column->get_background_color()->value, $columnsWithBackgrounds));
			if((count($columnBackgrounds) === 1) && ($this->backgroundColor->value === $columnBackgrounds[0])) {
				$updatedInnerComponents = [];
				foreach($innerComponents as $column) {
					$column->set_background_color(null);
					$updatedInnerComponents[] = $column;
				}
			}
		}

		$this->innerComponents = $updatedInnerComponents ?? $innerComponents;
	}

	function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if($this->allowStacking) {
			$attributes['data-allow-layout-stacking'] = 'true';
		}

		$attributes['data-count'] = $this->qty;

		return $attributes;
	}
}
