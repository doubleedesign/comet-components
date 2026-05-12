<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::MAIN, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Column extends UIComponent {
    use BackgroundColor;
    use LayoutAlignment;

    /**
     * @var ?string $width
     * @description Optionally set the width of the column, if you absolutely must do it explicitly instead of in your own CSS
     *              (ideally this should not be set in HTML, but things like the WP block editor kind of make it necessary to handle this use case).
     *              Note: This may be overridden to stack columns on small viewports.
     */
    protected ?string $width;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Columns.Column.column');
        $this->set_layout_alignment($attributes);
        $this->set_background_color($attributes);
        $this->width = $attributes['width'] ?? null;

		if($this->get_shortname() !== 'column') {
			$this->set_bem_element('column');
			$this->set_bem_modifier($this->get_shortname());
		}
    }

    public function get_width() {
        return $this->width;
    }

    public function set_width(?string $width): void {
        $this->width = $width;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        if (isset($this->width)) {
            $classes[] = $this->get_bem_prefix() . '--has-own-width';
        }

        return $classes;
    }

    protected function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if (isset($this->width)) {
            $styles['width'] = $this->width;
            $styles['flex-basis'] = $this->width;
        }

        return $styles;
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
            'tag'          => $this->tagName->value,
            'classes'      => $this->get_filtered_classes(),
            'attributes'   => $this->get_html_attributes(),
            'children'     => $this->innerComponents
        ])->render();
    }
}
