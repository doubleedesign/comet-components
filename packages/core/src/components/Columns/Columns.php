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
    private bool $shouldBeWrapped = false;

    public function __construct(array $attributes, array $innerComponents) {
        $this->qty = count($innerComponents);
        $this->allowStacking = $attributes['allowStacking'] ?? null;
        $this->set_layout_alignment($attributes);
        $this->set_background_color($attributes);

        // If all column widths are the same, remove them so unnecessary inline styles are not included in the final HTML
        $columnWidths = array_map(function($column) {
            if (!$column instanceof Column) return 0;

            return $column->get_width();
        }, $innerComponents);
        if (count(array_unique($columnWidths)) === 1) {
            array_walk($innerComponents, function(&$column) {
                if (!$column instanceof Column) return;

                $column->set_width(null);
            });
        }

        // If this component has its own shortname, wrap the content so we still get the appropriate class names for column styling automatically
        $this->shouldBeWrapped = isset($attributes['shortName']) && $attributes['shortName'] !== 'columns';
        if ($this->shouldBeWrapped) {
            $innerAttrs = array(
                ...Utils::array_pick($attributes, ['vAlign', 'hAlign']),
                'shortName'                  => 'columns',
                'data-count'                 => $this->qty
            );
            if ($this->allowStacking !== null && $this->allowStacking !== true && $this->allowStacking !== 'true') {
                $innerAttrs['data-allow-layout-stacking'] = 'false';
            }
            $wrappedInnerComponents = [new Group($innerAttrs, $innerComponents)];
        }

        // Finally, create the component with all the transformed stuff
        parent::__construct($attributes, $wrappedInnerComponents ?? $innerComponents, 'components.Columns.columns');
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (!$this->shouldBeWrapped) {
            $attributes['data-count'] = $this->qty;

            if (isset($this->hAlign) && !$this->hAlign->isDefault()) {
                $attributes['data-halign'] = $this->hAlign->value;
            }

            if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
                $attributes['data-valign'] = $this->vAlign->value;
            }

            if ($this->allowStacking !== null && $this->allowStacking !== true && $this->allowStacking !== 'true') {
                $attributes['data-allow-layout-stacking'] = 'false';
            }
        }

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
