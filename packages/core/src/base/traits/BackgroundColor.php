<?php
namespace Doubleedesign\Comet\Core;

trait BackgroundColor {
    /**
     * @var ThemeColor|ThemeGradient|null $backgroundColor
     * @description The background colour of the component.
     */
    protected ThemeColor|ThemeGradient|null $backgroundColor = null;

    /**
     * @param  array  $attributes
     * @param  ThemeColor|null  $fallback
     * @description Retrieves the relevant properties from the component $attributes array or component defaults, validates them, and assigns them to the corresponding component instance field.
     *
     * @return void
     */
    protected function set_background_color(array $attributes, ?ThemeColor $fallback = null): void {
        // Set from passed-in attributes if set
        if (isset($attributes['backgroundColor'])) {
            $this->backgroundColor = $this->get_from_string_or_theme_color($attributes['backgroundColor']);
        }

        // If no passed-in attribute (or applying it failed), check component defaults
        if (!$this->backgroundColor) {
            $class = static::class;
            $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
            $defaults = Config::getInstance()->get_component_defaults($classShortname);
            if (isset($defaults['backgroundColor'])) {
                $this->backgroundColor = $this->get_from_string_or_theme_color($defaults['backgroundColor']);
            }
        }

        // If still null, use the passed-in fallback if there is one
        if (!isset($this->backgroundColor) && isset($fallback)) {
            $this->backgroundColor = $this->get_from_string_or_theme_color($fallback);
        }

        // Only keep the set background color if this is nested OR the background color is different from the global background
        // So we don't set background attributes on top-level components when not needed
        // TODO: This needs to be fixed to account for PageSections having different backgrounds without enforcing nested state on their inner components
        //        $globalBackground = Config::getInstance()->get_global_background();
        //        $isSameAsGlobal = isset($this->backgroundColor) && $this->backgroundColor->value === $globalBackground;
        //        $isNested = (isset($this->isNested) && $this->isNested) || (isset($attributes['isNested']) && $attributes['isNested']);
        //        if ((!$isNested && $isSameAsGlobal) || !isset($attributes['backgroundColor'])) {
        //            $this->backgroundColor = null;
        //        }
    }

    private function get_from_string_or_theme_color($value): ThemeColor|ThemeGradient|null {
        if ($value instanceof ThemeColor) {
            return $value;
        }
        if ($value instanceof ThemeGradient) {
            return $value;
        }
        if ($value === null) {
            return null;
        }

        return ThemeColor::tryFrom($value) ?? ThemeGradient::tryFrom($value) ?? null;
    }

    /**
     * @description Get the background colour of the component; intended for use when only one is expected (primarily for backwards compatibility).
     *              If both inner and outer background colours are set, this will return the outer colour only.
     *
     * @return ThemeColor|ThemeGradient|null
     */
    public function get_background_color(): ThemeColor|ThemeGradient|null {
        return $this->backgroundColor;
    }

    /**
     * @description Allows the background colour of a component to be set
     * based on contextual factors not available at instantiation.
     * This is generally expected to be used to update children,
     * so it is not expected that gradients will be set here.
     * @param  string|null|ThemeColor  $backgroundColors
     *
     * @return void
     */
    public function update_background_color(ThemeColor|string|null $backgroundColors): void {
        $this->backgroundColor = $this->get_from_string_or_theme_color($backgroundColors);
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
        if ($this->get_background_color() !== null && isset($this->innerComponents)) {
            $this->remove_redundant_background_colors();
        }

        // If this component does not have a background set but its children all have the same background and/or no background,
        // remove the backgrounds from the children and apply that singular set background to this component
        if (!$this->backgroundColor && isset($this->innerComponents)) {
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
            if (method_exists($child, 'get_background_color')) {
                if ($child->get_background_color() !== null) {
                    return $child->get_background_color() === $this->backgroundColor;
                }
            }

            return false;
        });

        if (count($childrenWithSameBackground) > 0) {
            $updatedInnerComponents = array_map(function($child) {
                if (method_exists($child, 'get_background_color')) {
                    if (method_exists($child, 'update_background_color')) {
                        if ($child->get_background_color() === $this->backgroundColor) {
                            $child->update_background_color(null);
                        }
                    }
                    else if (method_exists($child, 'update_background_colors')) {
                        if ($child->get_background_color() === $this->backgroundColor) {
                            $child->update_background_colors(new BackgroundCollection(null, null));
                        }
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
            if (method_exists($child, 'get_background_color')) {
                if ($child->get_background_color() === null) {
                    return $carry;
                }
                if (!in_array($child->get_background_color(), $carry)) {
                    $carry[] = $child->get_background_color();
                }
            }

            return $carry;
        }, []);

        // If there is one colour left standing, set it as this component's background and remove it from the children
        if (count($childBackgrounds) === 1) {
            $this->update_background_color($childBackgrounds[0]);
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
