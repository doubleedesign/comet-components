<?php
namespace Doubleedesign\Comet\Core;

/**
 * Columns component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Organise content visually with a column-based layout.
 */
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

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $updatedInnerComponents ?? $innerComponents, 'components.Columns.columns');
        $this->qty = count($innerComponents);
        $this->allowStacking = $attributes['allowStacking'] ?? $attributes['isStackedOnMobile'] ?? true;

        // If all column widths are the same, remove them so unnecessary inline styles are not included in the final HTML
        $columnWidths = array_map(function($column) {
            return $column->get_width();
        }, $innerComponents);
        if (count(array_unique($columnWidths)) === 1) {
            $updatedInnerComponents = [];
            foreach ($innerComponents as $column) {
                $column->set_width(null);
                $updatedInnerComponents[] = $column;
            }
        }

        $this->innerComponents = $updatedInnerComponents ?? $innerComponents;
    }

    public function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->allowStacking) {
            $attributes['data-allow-layout-stacking'] = 'true';
        }

        $attributes['data-count'] = $this->qty;

        return $attributes;
    }
}
