<?php
namespace Doubleedesign\Comet\Core;

trait BackgroundColorMulti {
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
        // Backwards compatibility and components that we only ever expect to have a single background colour:
        // Allow for 'backgroundColor', but prefer newer backgroundColors for those that use multiple
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
     * @return ThemeColor|null
     */
    public function get_background_color(): ?ThemeColor {
        if (isset($this->backgroundColors->outer) && isset($this->backgroundColors->inner)) {
            error_log("Warning:" . static::class . " has both outer and inner background colors set, but you called get_background_colour(), which returns the outer colour only. If this was not intentional, you might want get_background_colours().");
        }

        return $this->backgroundColors->outer ?? $this->backgroundColors->inner ?? null;
    }

    public function update_background_colors(?BackgroundCollection $backgroundColors): void {
        $this->backgroundColors = $backgroundColors;
    }

}
