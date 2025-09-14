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

    public function __construct(array $attributes, array $innerComponents, string $bladeFile = 'components.Container.container') {
        parent::__construct($attributes, $innerComponents, $bladeFile);
        $this->set_size_from_attrs($attributes);
        $this->gradient = $attributes['gradient'] ?? null;
        $this->withWrapper = $attributes['withWrapper'] ?? $this->withWrapper;
    }

    /**
     * Classes to be applied to the container element, filtering out CMS size classes and adjusting for withWrapper
     *
     * @return array<string>
     */
    protected function get_filtered_classes(): array {
        $classes = array_filter(parent::get_filtered_classes(), function($class) {
            // Filter out WordPress + other classes used for the size (size is applied elsewhere)
            return !in_array($class, ['is-style-wide', 'is-style-fullwidth', 'is-style-narrow', 'container--wide', 'container--fullwidth', 'container--narrow']);
        });

        if (!$this->withWrapper) {
            $classes[] = 'layout-block';
            // Replace BEM name (context + shortname) with just the context
            // (with a wrapper, it should have the context on the wrapper and the BEM name here)
            $classes = array_filter($classes, fn($class) => $class !== $this->get_bem_name());
            array_push($classes, $this->context);
            $filtered = array_unique(array_merge($classes, [$this->shortName]));
        }
        else {
            // Do not include the shortname here - we can use CSS to target classes ending in __container (the BEM name) instead of doubling up
            $filtered = array_unique($classes);
        }

        // Sort them so the context is always first if present
        usort($filtered, function($a, $b) {
            if ($a === $this->context) {
                return -1;
            }
            if ($b === $this->context) {
                return 1;
            }

            return 0;
        });

        return $filtered;
    }

    /**
     * Attributes to always be applied to the container element, whether it has a wrapper or not
     *
     * @return array<string, string>
     */
    protected function get_inner_attributes(): array {
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

        return $attributes;
    }

    /**
     * Outer classes to use if withWrapper is true
     *
     * @return string[]
     */
    protected function get_outer_classes(): array {
        if ($this->isNested) {
            return !empty($this->context) ? [$this->context] : [];
        }

        return [$this->context, 'layout-block', 'page-section'];
    }

    /**
     * Attributes applied to the wrapper if withWrapper is true, or to the container element if not
     *
     * @return array<string, string>
     */
    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->backgroundColor)) {
            $attributes['data-background'] = $this->backgroundColor->value;
        }
        else if (isset($this->gradient)) {
            $attributes['data-background'] = 'gradient-' . $this->gradient;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'             => $this->tagName->value,
            'withWrapper'     => $this->withWrapper,
            'outerClasses'    => $this->get_outer_classes(),
            'attributes'      => $this->get_html_attributes(),
            'innerAttributes' => $this->get_inner_attributes(),
            'classes'         => $this->get_filtered_classes_string(),
            'children'        => $this->innerComponents
        ])->render();
    }
}
