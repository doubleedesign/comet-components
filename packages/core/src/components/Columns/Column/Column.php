<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::MAIN, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Column extends UIComponent {
    use BackgroundColor;
    use LayoutAlignment;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Columns.Column.column');
        $this->set_layout_alignment($attributes);
        $this->set_background_color($attributes);

        if ($this->get_shortname() !== 'column') {
            $this->set_bem_element('column');
            $this->set_bem_modifier($this->get_shortname());
        }
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->get_background_color() !== null) {
            $attributes['data-background'] = $this->get_background_color()->value;
        }

        if (isset($this->hAlign) && !$this->hAlign->isDefault()) {
            $attributes['data-halign'] = $this->hAlign->value;
        }

        if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
            $attributes['data-valign'] = $this->vAlign->value;
        }

        return $attributes;
    }

    public function update_context(string $context, ?Renderable $parent = null): static {
        if ($this->get_shortname() !== 'column') {
            return parent::update_context($context, $parent)
                ->set_bem_element('column')
                ->set_bem_modifier($this->get_shortname()
                );
        }

        return parent::update_context($context, $parent);
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
