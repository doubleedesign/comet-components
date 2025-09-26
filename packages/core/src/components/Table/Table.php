<?php
namespace Doubleedesign\Comet\Core;

/**
 * Table component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display tabular data with support for responsive stacking, sticky headers and footers, row headers, and merged cells.
 */
#[AllowedTags([Tag::TABLE])]
#[DefaultTag(Tag::TABLE)]
class Table extends Renderable {
    /**
     * @var ?bool|null $allowStacking
     * @description Optionally specify whether to adapt the layout by stacking columns when the viewport or container is narrow. Do not set if your theme will handle it on a case-by-case basis with custom CSS.
     */
    protected ?bool $allowStacking = null;

    /**
     * @var ?string sticky
     * @supported-values header, first-column
     * @description Make the header "sticky" when the table is large enough to scroll vertically, or make the first column "sticky" when the table is large enough to scroll horizontally; designed for use with <thead> or the first cells all being <th scope="row"> elements
     */
    protected ?string $sticky;

    /**
     * @var TableCaption|array|null $caption
     * @description Caption object, or content and attributes corresponding to a Caption object
     */
    protected TableCaption|array|null $caption = null;

    /**
     * @var array<array<TableHeaderCell>> $thead
     * @description Array of rows of TableHeaderCells for the table header
     */
    private array $thead;

    /**
     * @var array<array<TableCell>> $tbody
     * @description Array of rows of TableCells or TableHeaderCells for the table body
     */
    private array $tbody;

    /**
     * @var array<array<TableCell>> $tfoot
     * @description Array of rows of TableCells for the table footer
     */
    private array $tfoot;

    /**
     * @param  array  $attributes
     * @param array<string, array<TableCell|array> $data - Associative array with thead, tbody and tfoot keys, which have indexed arrays of row data. Multiple formats are supported to suit different requirements.
     *
     * Thead, tbody, and tfoot can be:
     *  - an indexed array of TableCell or TableHeaderCell objects, or arrays of data that can be turned into a TableCell or TableHeaderCell object
     *  - an associative array with 'attributes' and 'cells' keys, where attributes are for the row and the cells follow the same structure as above.
     * Tbody can also be:
     * - an indexed array of arrays following the attributes+cells associative format. This allows multiple <tbody> sections for grouping data in a single table if required.
     *   FIXME Indexed array of cells for a single tbody (instead of cells+attributes) would be misinterpreted as this. Need to account for that.
     */
    public function __construct(array $attributes, array $data) {
        parent::__construct($attributes, 'components.Table.table');
        $this->allowStacking = $attributes['allowStacking'] ?? $attributes['isStackedOnMobile'] ?? null;
        $this->sticky = $attributes['sticky'] ?? false;

        // For some unknown reason, WordPress doesn't like me setting the attribute to "caption" in BlockRenderer.php in the plugin, but tableCaption works,
        // but since I really prefer just 'caption' we need to handle both options here
        if (isset($attributes['caption'])) {
            $this->caption = $attributes['caption'] instanceof TableCaption
                ? $attributes['caption']
                : new TableCaption($attributes['caption']['attributes'], $attributes['caption']['content']);
        }
        else if (isset($attributes['tableCaption'])) {
            $this->caption = $attributes['tableCaption'] instanceof TableCaption
                ? $attributes['tableCaption']
                : new TableCaption($attributes['tableCaption']['attributes'], $attributes['tableCaption']['content']);
        }

        $this->thead = $this->process_rows($data['thead'] ?? []);
        $this->tbody = $this->process_tbody($data['tbody'] ?? []);
        $this->tfoot = $this->process_rows($data['tfoot'] ?? []);
    }

    /**
     * Process tbody data into an array of <tbody> sections, each with an array of rows.
     * This allows for multiple <tbody> sections for grouping data in a table if required,
     *
     * @param  array  $tbody
     *
     * @return array[]
     */
    private function process_tbody(array $tbody): array {
        $indexed = array_is_list($tbody);
        if ($indexed) {
            return array_map(function($section) {
                // Handle attributes+rows format, which is to allow the tbody to have custom attributes added to its HTML
                if (isset($section['rows'])) {
                    return [
                        'attributes' => $section['attributes'] ?? [],
                        'rows'       => $this->process_rows($section['rows'])
                    ];
                }

                // Handle indexed array of rows in cells+attributes format (nothing special for the tbody)
                else if (isset($section['cells'])) {
                    return ['rows' => $this->process_rows($section)];
                }

                // What about attributes + indexed array of cells (not intended, but could happen)?
                // TODO These need more thorough testing
                else if (isset($section['attributes'])) {
                    // What about an indexed array of cells with attributes for the tbody - now rows key?
                    // (Not intended, but could happen, so let's account for it)
                    // First, if cells are in a labelled field
                    if (isset($section['cells'])) {
                        return [
                            'attributes' => $section['attributes'],
                            'rows'       => $this->process_rows($section['cells'])
                        ];
                    }

                    // Otherwise, assume it's just an indexed array of cells without a labelled key
                    return [
                        'attributes' => $section['attributes'],
                        'rows'       => $this->process_rows(array_values($section))
                    ];
                }

                // Otherwise, we assume an array of cells (no row attributes), so pass through an indexed array of cells
                return $this->process_rows(array_values($section));
            }, $tbody);
        }
        else {
            return $this->process_rows($tbody);
        }
    }

    private function process_rows(array $rows): array {
        if (empty($rows)) {
            return [];
        }

        // Rows can just be an array of cells (not labelled),
        // or have two levels (attributes and cells) so they can have their own attributes too.
        // This normalises both formats to the latter.
        // TODO Add some attribute validation/processing for rows
        return array_map(function($row) {
            return array(
                'cells' => array_map(function($cell) {
                    if ($cell instanceof TableHeaderCell or $cell instanceof TableCell) {
                        return $cell;
                    }

                    if (isset($cell['attributes']['tagName']) && $cell['attributes']['tagName'] === 'th') {
                        return new TableHeaderCell($cell['attributes'], $cell['content']);
                    }

                    return new TableCell($cell['attributes'], $cell['content']);
                }, $row['cells'] ?? $row),
                'attributes' => $row['attributes'] ?? []
            );
        }, $rows);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        // Only add stacking attribute if it is explicitly set.
        // It's the kind of thing that's necessary for generic use cases where admins would choose to enable or disable it,
        // but in many bespoke themes/sites it should be taken care of in specific CSS rather than bloating the HTML.
        if ($this->allowStacking !== null && $this->allowStacking) {
            $attributes['data-allow-layout-stacking'] = 'true';
        }

        if ($this->sticky) {
            $attributes['data-sticky'] = $this->sticky;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        // TODO: How to render a colgroup if the first column should be sticky, so that the css can be applied to that?

        echo $blade->make($this->bladeFile, [
            'bemPrefix'  => $this->get_bem_prefix(),
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'thead'      => $this->thead,
            'tbody'      => $this->tbody,
            'tfoot'      => $this->tfoot,
            'caption'    => $this->caption
        ])->render();
    }
}
