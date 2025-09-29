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
class Container extends LayoutComponent {
    use LayoutContainerSize;

    /**
     * @var bool|null $withWrapper
     * @description Whether to wrap the container element so that the background is full-width
     */
    protected ?bool $withWrapper = true;

    /**
     * @var string|null $gradient
     * @description Name of a gradient to use for the background (requires accompanying CSS to be defined)
     */
    protected ?string $gradient; // TODO: Not limited by a trait because implementations could have all kinds of gradients they handle themselves

    /**
     * @var Tag|null $wrapperTag
     * @description Store a reference to the provided tag for use in the wrapping PageSection if applicable
     */
    private ?Tag $wrapperTag = Tag::SECTION;

    public function __construct(array $attributes, array $innerComponents, string $bladeFile = 'components.Container.container') {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->set_size_from_attrs($attributes);
        $this->gradient = $attributes['gradient'] ?? null;
        $this->withWrapper = $attributes['withWrapper'] ?? $this->withWrapper;

        if ($this->withWrapper) {
            if ($this->get_context()) {
                // The wrapping PageSection takes the tagName given,
                $this->wrapperTag = $this->tagName;
                // ... so we override the Container's tag so we don't get stuff like section -> section
                $this->set_tag('div');
                // The block should already be set by the context trait, so we add container here so this becomes theContext__container
                $this->set_bem_element('container');
            }
        }
        else {
            // If rendering without a wrapper, we want just the block (as set in the trait), not theBlock__container
            // (we manually add 'container' class in get_filtered_classes so we get "theBlock container")
            $this->set_bem_element(null);
        }
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

        if (!$this->withWrapper) {
            if (isset($this->backgroundColor)) {
                $attributes['data-background'] = $this->backgroundColor->value;
            }
            else if (isset($this->gradient)) {
                $attributes['data-background'] = 'gradient-' . $this->gradient;
            }
        }

        return $attributes;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        if (!$this->withWrapper) {
            array_push($classes, 'container');
        }

        return array_unique($classes);
    }

    protected function render_with_wrapper(): void {
        $inner = $this;
        $inner->set_is_nested(true); // Prevent infinite loop

        $withWrapper = new PageSection([
            'shortName'       => $this->get_shortname() === 'container' ? null : $this->get_shortname(),
            'context'         => $this->get_context(),
            'tagName'         => $this->wrapperTag->value,
            'backgroundColor' => $this->backgroundColor ?? null,
            'gradient'        => $this->gradient ?? null,
        ], [$inner]);
        $withWrapper->render();
    }

    protected function render_standalone(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'             => $this->tagName->value,
            'withWrapper'     => $this->withWrapper,
            'attributes'      => $this->get_html_attributes(),
            'classes'         => $this->get_filtered_classes(),
            'children'        => $this->innerComponents
        ])->render();
    }

    public function render(): void {
        if (!$this->get_is_nested()) {
            $this->render_with_wrapper();
        }
        else {
            $this->render_standalone();
        }
    }
}
