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
    use BackgroundColor;

    /**
     * @var int|mixed $qty
     * @description The number of layout columns to use; by default automatically determined by the number of inner components.
     *              Can be explicitly set when you want to use the columnLayout option (e.g., two columns laid out as 1/3 + 2/3)
     *              and/or hAlign option (e.g., 2 one-third columns centered).
     *              The maximum supported by the CSS is 6, so if a larger number is provided it will be adjusted.
     */
    protected int $qty = 2;

    /**
     * @var int $actualQty
     * @description The actual number of inner components provided, which may be different than the $qty specified for layout purposes.
     */
    private int $actualQty = 0;

    /**
     * @var string $columnLayout
     * @description How to lay out inner columns when there is fewer than the $qty allowed for.
     *              Which values are supported can depend on the number of content columns provided
     * 		        and the viewport size (e.g., expand-middle will only work for odd numbers of inner Columns,
     *              and only when there is enough horizontal space for all columns to be on the same row).
     * @supported-values even, expand-first, expand-last, expand-middle
     */
    protected string $columnLayout = 'even';

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
        // Allow qty to be explicitly specified so consumers can do things like 2 columns in a three-column layout that would leave 1/3 space
        $this->actualQty = count($innerComponents);
        $this->columnLayout = $attributes['columnLayout'] ?? $this->columnLayout;
        $this->qty = $attributes['qty'] ?? $this->actualQty;
        $this->allowStacking = $attributes['allowStacking'] ?? null;
        $this->set_layout_alignment($attributes);
        $this->set_background_color($attributes);

        // Wrap the content so we still get the appropriate class names for column styling automatically if it has its own shortname,
        // and so container queries work properly - the wrapper is the container
        $attributes['shortName'] = isset($attributes['shortName'])
            ? ($attributes['shortName'] !== 'columns') ? $attributes['shortName'] : 'columns-wrapper'
            : 'columns-wrapper';
        $innerAttrs = array(
            'shortName'                  => 'columns',
            ...$this->get_columns_html_attributes()
        );
        if ($this->allowStacking !== null && $this->allowStacking !== true && $this->allowStacking !== 'true') {
            $innerAttrs['data-allow-layout-stacking'] = 'false';
        }
        $wrappedInnerComponents = new Group($innerAttrs, $innerComponents);

        // Finally, create the component with all the transformed stuff
        parent::__construct($attributes, [$wrappedInnerComponents], 'components.Columns.columns');
    }

    private function get_columns_html_attributes(): array {
        $attributes = [];
        $attributes['data-count'] = $this->actualQty;
        $attributes['data-cols'] = $this->validate_layout_col_count($this->qty);

        // We always want vAlign if set, that's not relevant to the column count stuff
        if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
            $attributes['data-valign'] = $this->vAlign->value;
        }

        // Only add the stacking attribute if it explicitly false - default behaviour is true and I don't like muddyin' up the HTML
        if ($this->allowStacking !== null && ($this->allowStacking === false)) {
            $attributes['data-allow-layout-stacking'] = 'false';
        }

        // Return early if there are no actual columns, to prevent DivideByZero errors in the modulus checks below
        if ($this->actualQty === 0) {
            return $attributes;
        }

        // If the actual number of columns does not evenly divide into the specified qty, add the attributes to handle the layout
        // (e.g., we don't need them if qty is 4 but actualQty is 8, but we do if qty is 4 and actualQty is 3)
        // Generally we don't want more than 4 but this provides some allowance for that
        $addLayoutAttributes = $this->actualQty % $this->qty !== 0;
        if ($addLayoutAttributes) {
            $attributes['data-column-layout'] = $this->columnLayout;

            if (isset($this->hAlign) && !$this->hAlign->isDefault()) {
                $attributes['data-halign'] = $this->hAlign->value;
            }
        }

        return $attributes;
    }

    private function validate_layout_col_count($count): int {
        if ($count === 0) {
            return 2; // return early if there are no columns to prevent DivisionByZero errors in the modulus checks below
        }

        if ($count <= 6) {
            return $count;
        }

        if ($count % 6 === 0) {
            return 6;
        }

        if ($count % 4 === 0) {
            return 4;
        }

        if ($count % 3 === 0) {
            return 3;
        }

        return 2;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->get_background_color() !== null) {
            $attributes['data-background'] = $this->get_background_color()->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
