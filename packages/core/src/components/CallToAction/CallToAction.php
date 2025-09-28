<?php
namespace Doubleedesign\Comet\Core;

/**
 * Call-To-Action component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Highlight important information and prompt the user to take action.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class CallToAction extends Container {
    use ColorTheme;
    use LayoutOrientation;

    /**
     * @var array<Heading|Paragraph|ListComponent|ButtonGroup> $innerComponents
     */
    protected array $innerComponents;

    /**
     * @var bool $isNested
     * @description Whether this CallToAction is nested inside another LayoutComponent
     * @default-value true
     */
    protected bool $isNested = false;

    /**
     * @var ContainerSize|null $innerSize
     * @description The size of the inner container. If not set, defaults to the size of the outer container. Allows for a section to have a different, wider background than the inner block.
     * @default-value null
     */
    protected ?ContainerSize $innerSize = null;

    /**
     * @param  array  $attributes
     * @param  array<Heading|Paragraph|ButtonGroup>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        $this->withWrapper = true;
        $this->isNested = isset($attributes['isNested']) ? filter_var($attributes['isNested'], FILTER_VALIDATE_BOOLEAN) : $this->isNested;
        $this->innerSize = isset($attributes['innerSize']) ? ContainerSize::tryFrom($attributes['innerSize']) : $this->innerSize;
        $this->set_color_theme_from_attrs($attributes);
        $this->set_background_color_from_attrs($attributes);
        $this->set_orientation_from_attrs($attributes);
        if (!isset($attributes['tagName']) && !$this->isNested) {
            $this->tagName = Tag::SECTION;
        }

        parent::__construct($attributes, $innerComponents);
    }

    protected function get_outer_classes(): array {
        $classes = parent::get_outer_classes();

        if ($this->isNested) {
            // Replace BEM name (context + shortname) with just the context
            // (with a wrapper, it should have the context on the wrapper and the BEM name here)
            $classes = array_filter($classes, fn($class) => $class !== $this->get_bem_prefix());
            array_push($classes, $this->get_context());
        }

        array_push($classes, 'container');

        return array_unique($classes);
    }

    // This is the outer container attributes
    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->size)) {
            $attributes['data-size'] = $this->size->value;
        }

        unset($attributes['data-halign']); // we put this on the inner container
        unset($attributes['data-valign']); // we put this on the inner container

        return $attributes;
    }

    protected function get_inner_attributes(): array {
        $attributes = parent::get_inner_attributes();

        $attributes['data-orientation'] = 'vertical';

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }
        if (isset($this->innerSize)) {
            $attributes['data-size'] = $this->innerSize->value;
        }

        return $attributes;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Replace '__call-to-action' with '__container'
        return array_map(fn($class) => str_replace('__call-to-action', '__container', $class), $classes);
    }
}
