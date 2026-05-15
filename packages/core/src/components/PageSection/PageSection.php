<?php
namespace Doubleedesign\Comet\Core;

// TODO: A WordPress block needs to be created for this, with it allowing children and the controls for the children automatically updating the available sizes according to the size set there for the PageSection.

/**
 * PageSection component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.1.0
 * @description A basic page element to group nested content that can have its semantics, own sizes and alignments within the bounds of this wrapper's size.
 *              This component is intended for layout purposes only, not intended to semantically group content.
 *              Unlike Container, it does not enforce nested state upon its inner components, and unlike UIComponent and its children it does not have or propagate context.
 *
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class PageSection extends Renderable {
    use BackgroundColor;
    use LayoutContainerSize;
    use ShortName;

    /**
     * @var array<Renderable> $innerComponents
     * @description Inner components to be rendered within this component
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, 'components.PageSection.page-section');
        $this->innerComponents = $innerComponents;
        $this->set_size($attributes);
        $this->set_background_color($attributes);
        $this->simplify_all_background_colors();

        array_walk($this->innerComponents, function(&$component) {
            if ($component->get_size() === $this->size) {
                $component->set_size(null);
            }
        });
    }

    public function get_filtered_classes(): array {
        return array_unique(array_merge(
            parent::get_filtered_classes(),
            [$this->get_shortname()]
        ));
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->size)) {
            $attributes['data-size'] = $this->size->value;
        }

        if ($this->get_background_color() !== null) {
            $attributes['data-background'] = $this->backgroundColor->value;
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
