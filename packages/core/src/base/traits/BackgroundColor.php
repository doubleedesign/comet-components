<?php
namespace Doubleedesign\Comet\Core;

trait BackgroundColor {
    /**
     * @var BackgroundCollection|null $backgroundColors
     * @description Key -> value pairs of background placement and colour keywords
     */
    protected ?BackgroundCollection $backgroundColors = null;

    /**
     * @param  array  $attributes
     * @description Retrieves the relevant properties from the component $attributes array or component defaults, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_background_colors(array $attributes): void {
        // Backwards compatibility: Allow for 'backgroundColor', but prefer newer backgroundColors
        $maybeAttr = $attributes['backgroundColors'] ?? $attributes['backgroundColor'] ?? null;
        if ($maybeAttr !== null) {
            $this->backgroundColors = $this->transform_to_collection($maybeAttr);
        }
        // If no passed-in attribute (or applying it failed), check component defaults
        else {
            $class = static::class;
            $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
            $defaults = Config::getInstance()->get_component_defaults($classShortname);
            if (isset($defaults['backgroundColors']) || isset($defaults['backgroundColor'])) {
                $this->backgroundColors = $this->transform_to_collection($defaults['backgroundColors'] ?? $defaults['backgroundColor']);
            }
        }

        // If the outer background is the same as the global background and this component is not nested,
        // remove it so we don't set background attributes on top-level components when not needed
        $isNested = (isset($this->isNested) && $this->isNested) || (isset($attributes['isNested']) && $attributes['isNested']);
        if (!$isNested) {
            $globalBackground = Config::getInstance()->get_global_background();
            $isSameAsGlobal = isset($this->backgroundColor->outer) && $this->backgroundColor->outer === $globalBackground;
            if ($isSameAsGlobal) {
                $this->backgroundColors = new BackgroundCollection(null, $this->backgroundColors->inner);
            }
        }
    }

    private function transform_to_collection($value): ?BackgroundCollection {
        // This was extracted out into the collection class for isolated testing and troubleshooting
        return BackgroundCollection::transform_to_collection($value);
    }

    /**
     * @description Get the background colours of the component.
     *
     * @return ?BackgroundCollection;
     */
    public function get_background_colors(): ?BackgroundCollection {
        return $this->backgroundColors;
    }

    /**
     * @description Get the background colour of the component; intended for use when only one is expected (primarily for backwards compatibility).
     *              If both inner and outer background colours are set, this will return the outer colour only.
     *
     * @return ThemeColor|ThemeGradient|null
     */
    public function get_background_color(): ThemeColor|ThemeGradient|null {
        if (isset($this->backgroundColors->outer) && isset($this->backgroundColors->inner)) {
            error_log("Warning:" . static::class . " has both outer and inner background colors set, but you called get_background_colour(), which returns the outer colour only. If this was not intentional, you might want get_background_colours().");
        }

        return $this->backgroundColors->outer ?? $this->backgroundColors->inner ?? null;
    }

    /**
     * @description Allows the background colour of a component to be set based on contextual factors not available at instantiation.
     * @param  string|null|ThemeColor  $backgroundColors
     *
     * @return void
     */
    public function update_background_color(ThemeColor|string|null $backgroundColors): void {
        $this->backgroundColors = $this->transform_to_collection($backgroundColors);
    }

    /**
     * @description Clean up duplication of background colours between this and its inner components simplify HTML and CSS. Runs either remove_redundant_background_colors() or set_background_colors_based_on_children() as appropriate.
     * NOTE: This must be run after the constructor and after update_background_color() to ensure the backgrounds and innerComponents are available
     *
     * @return void
     */
    public function simplify_all_background_colors(): void {
        // If all backgrounds set on direct children of this component are the same as this component's background,
        // remove the background from those children
        if (isset($this->backgroundColors->outer) && isset($this->innerComponents)) {
            $this->remove_redundant_background_colors();
        }

        // If this component does not have a background set but its children all have the same background and/or no background,
        // remove the backgrounds from the children and apply that singular set background to this component
        if (!$this->backgroundColors && isset($this->innerComponents)) {
            $this->set_background_colors_based_on_inner_components();
        }
    }

    /**
     * @description If this component has a background colour set, remove the same background from any children that have it to simplify HTML and CSS.
     * This is available to component classes because there are some components where we want to do this, but not assign a background colour to the component.
     *
     * @return void
     */
    protected function remove_redundant_background_colors(): void {
        // Bail if there's fewer than 2 inner components
        if (count($this->innerComponents) < 2) {
            return;
        }

        $childrenWithSameBackground = array_filter($this->innerComponents, function($child) {
            if (method_exists($child, 'get_background_colors')) {
                return $child->get_background_colors() === $this->backgroundColor;
            }

            return false;
        });

        if (count($childrenWithSameBackground) > 0) {
            $updatedInnerComponents = array_map(function($child) {
                if (method_exists($child, 'update_background_color') && method_exists($child, 'get_background_colors')) {
                    if ($child->get_background_colors() === $this->backgroundColor) {
                        $child->update_background_color(null);
                    }
                }

                return $child;
            }, $this->innerComponents);
        }

        $this->innerComponents = $updatedInnerComponents ?? $this->innerComponents;
    }

    /**
     * If this component does not have a background set but its children all have the same background and/or no background,
     * "hoist" that singular set background to this component and remove the backgrounds from the children
     *
     * @return void
     */
    protected function set_background_colors_based_on_inner_components(): void {
        // No need to set the background if it's already set
        if ($this->backgroundColor) {
            return;
        }

        // Bail if there's fewer than 2 inner components
        if (count($this->innerComponents) < 2) {
            return;
        }

        // Collect the child backgrounds, with in-place filtering to remove duplicates
        // But do not filter out null values, because that would set the background of a parent when it shouldn't
        // just because *some* children don't have an explicit background
        $childBackgrounds = array_reduce($this->innerComponents, function($carry, $child) {
            if (method_exists($child, 'get_background_colors')) {
                if (!in_array($child->get_background_colors(), $carry)) {
                    $carry[] = $child->get_background_colors();
                }
            }

            return $carry;
        }, []);

        // If there is one colour left standing, set it as this component's background and remove it from the children
        if (count($childBackgrounds) === 1) {
            $this->backgroundColor = $childBackgrounds[0];
            $updatedInnerComponents = array_map(function($child) {
                if (method_exists($child, 'update_background_color')) {
                    $child->update_background_color(null);
                }

                return $child;
            }, $this->innerComponents);
        }

        $this->innerComponents = $updatedInnerComponents ?? $this->innerComponents;
    }

}
