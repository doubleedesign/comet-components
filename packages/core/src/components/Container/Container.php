<?php
namespace Doubleedesign\Comet\Core;

/**
 * Container component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Create a section with semantic meaning that controls the maximum width of its contents.
 */
#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV, Tag::ARTICLE, Tag::FOOTER])]
#[DefaultTag(Tag::SECTION)]
class Container extends WrappedLayoutComponent {
    use LayoutContainerSize;
    use LayoutOrientation;

    /**
     * @var string|null $gradient
     * @description Name of a gradient to use for the background (requires accompanying CSS to be defined)
     */
    protected ?string $gradient; // TODO: Not limited by a trait because implementations could have all kinds of gradients they handle themselves

    public function __construct(array $attributes, array $innerComponents, string $bladeFile = 'components.Container.container') {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->set_size_from_attrs($attributes);
        $this->set_orientation_from_attrs($attributes, null);
        $this->gradient = $attributes['gradient'] ?? null;
        $this->set_is_nested(@$attributes['isNested'] ?? false);
    }

    /**
     * Nested state can be updated from outside (notably in the render methods of WrappedLayoutComponent)
     * so we need to make the relevant updates when that happens, not just in the constructor
     *
     * @param  bool  $isNested
     *
     * @return void
     */
    public function set_is_nested(?bool $isNested): void {
        parent::set_is_nested($isNested);
        if ($this->get_is_nested()) {
            // The wrapping PageSection takes the tagName given,
            // so we override the Container's tag so we don't get stuff like section -> section
            $this->set_tag('div');
            // The block should already be set by the context trait, so we add container here so this becomes theContext__container
            $this->set_bem_element('container');
        }
        else {
            // If rendering without a wrapper, we want just the block (as set in the trait), not theBlock__container
            // (we manually add 'container' class in get_filtered_classes so we get "theBlock container")
            $this->set_bem_element(null);
        }
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        if (!$this->get_is_nested()) {
            array_push($classes, 'container');
        }

        return array_unique($classes);
    }

    protected function get_html_attributes(): array {
        $attributes = [];
        if (isset($this->size) && $this->size !== ContainerSize::DEFAULT) {
            $attributes['data-size'] = $this->size->value;
        }

        if (isset($this->hAlign) && !$this->hAlign->isDefault()) {
            $attributes['data-halign'] = $this->hAlign->value;
        }

        if (isset($this->vAlign) && !$this->vAlign->isDefault()) {
            $attributes['data-valign'] = $this->vAlign->value;
        }

        // we don't check against a default here we only want orientation added to the HTML if explicitly set
        if (isset($this->orientation)) {
            $attributes['data-orientation'] = $this->orientation->value;
        }

        if ($this->get_is_nested()) {
            if (isset($this->backgroundColor)) {
                $attributes['data-background'] = $this->backgroundColor->value;
            }
            else if (isset($this->gradient)) {
                $attributes['data-background'] = 'gradient-' . $this->gradient;
            }
        }

        return $attributes;
    }

}
