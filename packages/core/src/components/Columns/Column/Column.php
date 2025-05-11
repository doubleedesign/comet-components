<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::MAIN, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Column extends LayoutComponent {
    /**
     * @var ?string $width
     * @description Optionally set the width of the column. Note: This may be overridden to stack columns on small viewports.
     */
    protected ?string $width;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct(
            array_merge(['context' => 'columns'], $attributes),
            $innerComponents,
            'components.Columns.Column.column');
        $this->width = $attributes['width'] ?? null;
        $this->tagName = isset($attributes['tagName']) ? Tag::tryFrom($attributes['tagName']) : Tag::DIV;
    }

    public function get_width() {
        return $this->width;
    }

    public function set_width(?string $width) {
        $this->width = $width;
    }

    public function get_filtered_classes(): array {
        $classes = array_merge([$this->shortName], parent::get_filtered_classes());

        if (isset($this->width)) {
            $classes[] = 'columns__column--has-own-width';
        }

        return $classes;
    }

    public function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if (isset($this->width)) {
            $styles['width'] = $this->width;
            $styles['flex-basis'] = $this->width;
        }

        return $styles;
    }
}
