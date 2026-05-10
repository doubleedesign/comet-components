<?php

namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV, Tag::ARTICLE, Tag::FOOTER, Tag::FIGURE])]
#[DefaultTag(Tag::SECTION)]
abstract class WrappedLayoutComponent extends LayoutComponent {
    /**
     * @var Tag|null $wrapperTag
     * @description Store a reference to the provided tag for use in the wrapping PageSection if applicable
     *              because child classes may override $this->tagName to avoid nesting sections in sections and similar
     */
    private ?Tag $wrapperTag;

    /**
     * @var array $ariaAttrs
     * @description Allow for aria-* attributes to be passed down to the wrapper explicitly
     */
    protected array $ariaAttrs;

    /**
     * @var array $dataAttrs
     * @description Allow for data-* attributes to be passed down to the container explicitly;
     *              useful for cases that aren't worth creating a trait or shared property for (e.g., Columns uses this for data-count)
     */
    protected array $dataAttrs;

    /**
     * @var bool $isWrapped
     * @description Internal flag to prevent double nesting when a component that extends this one is wrapped in another one.
     *              Different to isNested because that can be true from the beginning, whereas this is only set after the wrapper is applied.
     * 			    Passed down to Blade templates to handle avoiding double-ups.
     */
    private bool $isWrapped = false;

    public function __construct(array $attributes, array $innerComponents, string $bladeFile) {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->wrapperTag = $this->tagName;
        $this->ariaAttrs = array_filter($attributes, fn($key) => str_starts_with($key, 'aria-') || $key === 'role', ARRAY_FILTER_USE_KEY);
        $this->dataAttrs = array_filter($attributes, fn($key) => str_starts_with($key, 'data-'), ARRAY_FILTER_USE_KEY);
    }

    protected function render_standalone(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'             => $this->tagName->value,
            'isWrapped'       => $this->isWrapped,
            'attributes'      => $this->get_html_attributes(),
            'classes'         => $this->get_filtered_classes(),
            'children'        => $this->innerComponents
        ])->render();
    }

    final protected function render_with_wrapper(): void {
        $inner = $this;
        $inner->set_is_nested(true); // Prevent infinite loop
        $inner->isWrapped = true; // Passed down to Blade templates to handle avoiding double-ups

        $withWrapper = new PageSection([
            'shortName'       => $this->get_shortname(),
            'attributes'      => $this->get_html_attributes(),
            'tagName'         => $this->wrapperTag->value,
        ], [$inner]);
        $withWrapper->render();
    }

    final public function render(): void {
        if (!$this->get_is_nested()) {
            $this->render_with_wrapper();
        }
        else {
            $this->render_standalone();
        }
    }
}
