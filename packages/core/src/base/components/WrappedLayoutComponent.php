<?php

namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV, Tag::ARTICLE, Tag::FOOTER])]
#[DefaultTag(Tag::SECTION)]
abstract class WrappedLayoutComponent extends LayoutComponent {
    /**
     * @var Tag|null $wrapperTag
     * @description Store a reference to the provided tag for use in the wrapping PageSection if applicable
     *              because child classes may override $this->tagName to avoid nesting sections in sections and similar
     */
    private ?Tag $wrapperTag;
    private array $wrapperAttrs;
    private array $containerAttrs;
    private array $ariaAttrs;

    /**
     * @var bool $withContainer
     * @description Whether to wrap the inner components in a Container. Defaults to true if the component is not nested.
     *              Will be ignored if the component is already a Container or is nested.
     */
    protected bool $withContainer = true;

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
        $this->wrapperAttrs = array_merge(Utils::array_pick($attributes, ['id', 'backgroundColor', 'colorTheme']), $this->ariaAttrs);
        $this->containerAttrs = Utils::array_pick($attributes, ['hAlign', 'vAlign', 'size', 'orientation']);
        // Initially set withContainer to whatever is in the attributes
        $this->withContainer = $attributes['withContainer'] ?? $this->withContainer;
        // ...but then check the other conditions and update accordingly
        $this->withContainer = $this->should_add_container();

        $orphanedAttrs = array_diff(
            array_keys($attributes),
            array_merge(
                array_keys($this->wrapperAttrs),
                array_keys($this->containerAttrs),
                array_keys($this->ariaAttrs),
                ['shortName', 'context', 'isNested', 'tagName', 'classes'] // handled elsewhere
            )
        );
        if (count($orphanedAttrs) > 0) {
            trigger_error('WrappedLayoutComponent: The following attributes were not recognised and will be ignored: ' . join(', ', $orphanedAttrs), E_USER_WARNING);
        }

        // If this is not already a Container, wrap its contents in one
        // this saves having to add a container in almost every component that extends this class
        if (!$this instanceof Container && $this->withContainer) {
            $this->innerComponents = array(
                new Container([
                    'isNested' => true,
                    'context'  => $this->get_shortname(),
                    ...$this->containerAttrs
                ], $this->innerComponents)
            );
        }
    }

    private function should_add_container(): bool {
        if ($this instanceof Container) return false;

        if ($this->get_is_nested()) return false;

        return $this->withContainer;
    }

    public function get_is_wrapped(): bool {
        return $this->isWrapped;
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
            'tagName'         => $this->wrapperTag->value,
            ...$this->wrapperAttrs
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
