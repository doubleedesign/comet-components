<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::MAIN, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Column extends LayoutComponent {
    /**
     * @var ?string $width
     * @description Optionally set the width of the column, if you absolutely must do it explicitly instead of in your own CSS
     *              (ideally this should not be set in HTML, but things like the WP block editor kind of make it necessary to handle this use case).
     *              Note: This may be overridden to stack columns on small viewports.
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

    protected function get_filtered_classes(): array {
        $classes = array_merge([$this->shortName], parent::get_filtered_classes());

        if (isset($this->width)) {
            $classes[] = 'columns__column--has-own-width';
        }

        return $classes;
    }

    protected function get_inner_classes(): array {
        return [
            $this->shortName . '__inner',
            ...($this->context !== 'columns' ? [$this->context] : [])
        ];
    }

    public function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if (isset($this->width)) {
            $styles['width'] = $this->width;
            $styles['flex-basis'] = $this->width;
        }

        return $styles;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'          => $this->tagName->value,
            'classes'      => $this->get_filtered_classes(),
            'innerClasses' => implode(' ', $this->get_inner_classes()),
            'attributes'   => $this->get_html_attributes(),
            'children'     => $this->innerComponents
        ])->render();
    }
}
