<?php
namespace Doubleedesign\Comet\Core;

trait BackgroundColor {
    /**
     * @var ?ThemeColor $backgroundColor
     * @description Background colour keyword
     */
    protected ?ThemeColor $backgroundColor = null;

    /**
     * @param  array  $attributes
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_background_color_from_attrs(array $attributes): void {
        if (isset($attributes['backgroundColor'])) {
            if ($attributes['backgroundColor'] instanceof ThemeColor) {
                $this->backgroundColor = $attributes['backgroundColor'];
            }
            else {
                $this->backgroundColor = ThemeColor::tryFrom($attributes['backgroundColor']);
            }
        }
    }

    /**
     * @description Get the background colour of the component.
     *
     * @return ?ThemeColor
     */
    public function get_background_color(): ?ThemeColor {
        return $this->backgroundColor;
    }

    /**
     * @description Allows the background colour of a component to be set based on contextual factors not available at instantiation.
     * @param  string|null|ThemeColor  $backgroundColor
     *
     * @return void
     */
    public function set_background_color(ThemeColor|string|null $backgroundColor): void {
        if ($backgroundColor instanceof ThemeColor) {
            $this->backgroundColor = $backgroundColor;
        }
        else if (is_string($backgroundColor)) {
            $this->backgroundColor = ThemeColor::tryFrom($backgroundColor);
        }
        else {
            $this->backgroundColor = null;
        }
    }

    /**
     * @description Clean up duplication of background colours between this and its inner components simplify HTML and CSS. Runs either remove_redundant_background_colors() or set_background_color_based_on_children() as appropriate.
     * NOTE: This must be run after the constructor and after set_background_color_from_attrs() to ensure the backgrounds and innerComponents are available
     *
     * @return void
     */
    public function simplify_all_background_colors(): void {
        // If all backgrounds set on direct children of this component are the same as this component's background,
        // remove the background from those children
        if (isset($this->backgroundColor) && isset($this->innerComponents)) {
            $this->remove_redundant_background_colors();
        }

        // If this component does not have a background set but its children all have the same background and/or no background,
        // remove the backgrounds from the children and apply that singular set background to this component
        if (!$this->backgroundColor && isset($this->innerComponents)) {
            $this->set_background_color_based_on_inner_components();
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
                return $child->get_background_color() === $this->backgroundColor;
            }

            return false;
        });

        if (count($childrenWithSameBackground) > 0) {
            $updatedInnerComponents = array_map(function($child) {
                if (method_exists($child, 'set_background_color') && method_exists($child, 'get_background_color')) {
                    if ($child->get_background_color() === $this->backgroundColor) {
                        $child->set_background_color(null);
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
    protected function set_background_color_based_on_inner_components(): void {
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
                if (!in_array($child->get_background_color(), $carry)) {
                    $carry[] = $child->get_background_color();
                }
            }

            return $carry;
        }, []);

        // If there is one colour left standing, set it as this component's background and remove it from the children
        if (count($childBackgrounds) === 1) {
            $this->backgroundColor = $childBackgrounds[0];
            $updatedInnerComponents = array_map(function($child) {
                if (method_exists($child, 'set_background_color')) {
                    $child->set_background_color(null);
                }

                return $child;
            }, $this->innerComponents);
        }

        $this->innerComponents = $updatedInnerComponents ?? $this->innerComponents;
    }

}
