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
class Columns extends LayoutComponent {
	use NestedState;

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
        $this->set_layout_alignment($attributes);

        // If all column widths are the same, remove them so unnecessary inline styles are not included in the final HTML
        $columnWidths = array_map(fn($column) => $column->get_width(), $innerComponents);
        $updatedInnerComponents = count(array_unique($columnWidths)) === 1
            ? array_map(fn($column) => $column->set_width(null) ?: $column, $innerComponents)
            : $innerComponents;

        // Finally, create the component with all the transformed stuff
        parent::__construct($attributes, $updatedInnerComponents, 'components.Columns.columns');
    }

    /**
     * Get HTML attributes for the component
     * Note: In this case, this only applies when the component is nested, as when not nested the attributes are applied to the wrapper.
     *       We can't use this method to get them for that because the parent constructor needs to be called after the Group is created in order to include it,
     *       and we can't update them after the fact because Group doesn't actually support alignment properties - the HTML attributes are added explicitly (which isn't the best, but will do for now)
     *
     * @return array<string, string> Array of HTML attributes
     */
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
