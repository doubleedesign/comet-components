<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::TD])]
#[DefaultTag(Tag::TD)]
class TableCell extends TextElement {
    use BackgroundColor;

    /**
     * @var string|null $verticalAlign
     * @description Vertical alignment of the cell content
     * @supported-values top, middle, bottom
     */
    protected ?string $verticalAlign;

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Table.TableCell.table-cell');
        $this->set_text_align_from_attrs($attributes);
        $this->set_background_color_from_attrs($attributes);
        $this->verticalAlign = $attributes['verticalAlign'] ?? null;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }

        return $attributes;
    }

    public function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if (isset($this->verticalAlign)) {
            $styles['vertical-align'] = $this->verticalAlign;
        }

        return $styles;
    }
}
