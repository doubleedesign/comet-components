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
     * @description The number of columns to display; by default automatically determined by the number of inner components.
     *              Can be explicitly set when you want to use the columnLayout option (e.g., two columns laid out as 1/3 + 2/3)
     *              and/or hAlign option (e.g., 2 one-third columns centered).
     */
    protected int $qty = 2;
    private int $actualQty = 0;

    /**
     * @var string $columnLayout
     * @description How to lay out inner columns when there is fewer than the $qty allowed for.
     * @supported-values even, expand-first, expand-last
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
    private bool $shouldBeWrapped = false;

    public function __construct(array $attributes, array $innerComponents) {
        // Allow qty to be explicitly specified so consumers can do things like 2 columns in a three-column layout that would leave 1/3 space
        $this->actualQty = count($innerComponents);
        $this->qty = $attributes['qty'] ?? $this->actualQty;
        $this->allowStacking = $attributes['allowStacking'] ?? null;
        $this->set_layout_alignment($attributes);
        $this->set_background_color($attributes);

        // If this component has its own shortname, wrap the content so we still get the appropriate class names for column styling automatically
        $this->shouldBeWrapped = isset($attributes['shortName']) && $attributes['shortName'] !== 'columns';
        if ($this->shouldBeWrapped) {
            $innerAttrs = array(
                'shortName'                  => 'columns',
                ...$this->get_columns_html_attributes()
            );
            if ($this->allowStacking !== null && $this->allowStacking !== true && $this->allowStacking !== 'true') {
                $innerAttrs['data-allow-layout-stacking'] = 'false';
            }
            $wrappedInnerComponents = [new Group($innerAttrs, $innerComponents)];
        }

        // Finally, create the component with all the transformed stuff
        parent::__construct($attributes, $wrappedInnerComponents ?? $innerComponents, 'components.Columns.columns');
    }

    private function get_columns_html_attributes(): array {
        $attributes = [];

        $attributes['data-count'] = $this->qty;

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

        // We always want vAlign if set, that's not relevant to the column count stuff
        if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
            $attributes['data-valign'] = $this->vAlign->value;
        }

        // Only add the stacking attribute if it explicitly false - default behaviour is true and I don't like muddyin' up the HTML
        if ($this->allowStacking !== null && ($this->allowStacking === false)) {
            $attributes['data-allow-layout-stacking'] = 'false';
        }

        return $attributes;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->get_background_color() !== null) {
            $attributes['data-background'] = $this->get_background_color()->value;
        }

        if (!$this->shouldBeWrapped) {
            $attributes = array_merge($attributes, $this->get_columns_html_attributes());
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
