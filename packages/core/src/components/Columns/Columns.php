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
#[DefaultTag(Tag::SECTION)]
class Columns extends WrappedLayoutComponent {
    private int $qty;

    /**
     * @var bool $allowStacking
     * @description Option to explicitly specify whether adapt the layout by stacking columns when the viewport or container is narrow.
     *              Defaults to true behaviour but does not put the attribute in the HTML unless explicitly set.
     *              You generally do not want to set this if your theme will handle it on a case-by-case basis with custom CSS.
     */
    protected ?bool $allowStacking = null;

    /**
     * @var array<Column> $innerComponents
     * @description Inner column components
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        $this->qty = count($innerComponents);
        $this->allowStacking = $attributes['allowStacking'] ?? $attributes['isStackedOnMobile'] ?? null;
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->withContainer = $attributes['withContainer'] ?? !$this->get_is_nested() ?? $this->withContainer;
        $this->set_layout_alignment_from_attrs($attributes);

        // For nested instances, default to div tag unless specified otherwise in the attributes
        if ($this->get_is_nested() && !isset($attributes['tagName'])) {
            $this->set_tag('div');
        }

        // If all column widths are the same, remove them so unnecessary inline styles are not included in the final HTML
        $columnWidths = array_map(fn($column) => $column->get_width(), $innerComponents);
        $updatedInnerComponents = count(array_unique($columnWidths)) === 1
            ? array_map(fn($column) => $column->set_width(null) ?: $column, $innerComponents)
            : $innerComponents;

        // If the component will have a container added, also wrap the columns in a group so the container queries work properly
        $wrappedCols = new Group([
            'shortName'  => 'columns',
            'data-count' => $this->qty,
            ...($this->allowStacking !== null ? ['data-allow-layout-stacking' => $this->allowStacking ? 'true' : 'false'] : []),
        ], $updatedInnerComponents);
        $updatedInnerComponents = $this->withContainer ? [$wrappedCols] : $updatedInnerComponents;

        // Finally, create the component with all the transformed stuff
        parent::__construct($attributes, $updatedInnerComponents, 'components.Columns.columns');

        // ...and then update things that need data that is set by the parent constructor
        $wrappedCols->update_context($this->get_bem_prefix());
        array_walk($wrappedCols->innerComponents, function(&$column) {
            // If inner Column components do not have their own context explicitly set, add it from this component
            if ($column->get_context() === 'columns') {
                $column->update_context($this->get_bem_prefix());
            }
        });
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();
        $attributes['data-count'] = $this->qty;

        if (isset($this->hAlign) && !$this->hAlign->isDefault()) {
            $attributes['data-halign'] = $this->hAlign->value;
        }

        if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
            $attributes['data-valign'] = $this->vAlign->value;
        }

        if ($this->allowStacking !== null) {
            $attributes['data-allow-layout-stacking'] = $this->allowStacking ? 'true' : 'false';
        }

        return $attributes;
    }
}
