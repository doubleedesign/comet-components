<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::TH])]
#[DefaultTag(Tag::TH)]
class TableHeaderCell extends TableCell {
    /**
     * @var string|null $width
     * @description Fixed width of the cell, including units
     */
    protected ?string $width;

    public function __construct(array $attributes, string|LabelWithTooltip $content) {
        parent::__construct($attributes,
            gettype($content) === 'string' ? $content : $content->content,
        );
        $this->width = $attributes['width'] ?? null;
        $this->innerComponents = gettype($content) === 'string' ? [] : [$content];
    }

    public function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if ($this->width) {
            $styles['width'] = $this->width;
        }

        return $styles;
    }
}
