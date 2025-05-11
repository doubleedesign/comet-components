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
     * @var bool $allowStacking
     * @description Whether to adapt the layout by stacking columns when the viewport or container is narrow
     */
    protected bool $allowStacking = true;

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
     * @param array<string, array<TableCell|array> $data - Associative array with thead, tbody and tfoot keys, which have indexed arrays of rows of TableCells or arrays of attributes and content for TableCell objects
     */
    public function __construct(array $attributes, array $data) {
        parent::__construct($attributes, 'components.Table.table');
        $this->allowStacking = $attributes['allowStacking'] ?? $attributes['isStackedOnMobile'] ?? true;
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
        $this->tbody = $this->process_rows($data['tbody'] ?? []);
        $this->tfoot = $this->process_rows($data['tfoot'] ?? []);
    }

    private function process_rows(array $rows): array {
        return array_map(function($row) {
            return array_map(function($cell) {
                if ($cell instanceof TableHeaderCell or $cell instanceof TableCell) {
                    return $cell;
                }

                if ($cell['attributes']['tagName'] === 'th') {
                    return new TableHeaderCell($cell['attributes'], $cell['content']);
                }

                return new TableCell($cell['attributes'], $cell['content']);
            }, $row);
        }, $rows);
    }

    public function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->allowStacking) {
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
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'thead'      => $this->thead,
            'tbody'      => $this->tbody,
            'tfoot'      => $this->tfoot,
            'caption'    => $this->caption
        ])->render();
    }
}
