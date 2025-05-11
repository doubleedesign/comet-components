<?php
namespace Doubleedesign\Comet\Core;

/**
 * ButtonGroup component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Semantically and visually group buttons together.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ButtonGroup extends UIComponent {
    use LayoutAlignment;
    use LayoutOrientation;

    /**
     * @var array<Button>
     * @description Button objects to render inside the ButtonGroup
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.ButtonGroup.button-group');
        $this->set_orientation_from_attrs($attributes, Orientation::HORIZONTAL);
        $this->set_layout_alignment_from_attrs($attributes);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->orientation)) {
            $attributes['data-orientation'] = $this->orientation->value;
        }

        if (isset($this->hAlign)) {
            $attributes['data-halign'] = $this->hAlign->value;
        }

        if (isset($this->vAlign)) {
            $attributes['data-valign'] = $this->vAlign->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();

    }
}
