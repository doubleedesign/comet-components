<?php
namespace Doubleedesign\Comet\Core;

trait ColorTheme {
    /**
     * @var ?ThemeColor $colorTheme
     * @description Colour keyword for theming purposes.
     * May be used for headings, buttons, accents, and other UI elements according to the component implementation.
     */
    protected ?ThemeColor $colorTheme;

    /**
     * @param  array  $attributes
     * @param  ?ThemeColor  $default
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_color_theme_from_attrs(array $attributes, ?ThemeColor $default = null): void {
        // Allow for elements to not have a colour theme set and instead inherit from their parent (assuming CSS is set up for that)
        if (isset($attributes['colorTheme']) && ($attributes['colorTheme'] === 'inherit')) {
            $this->colorTheme = null;

            return;
        }

        // Set from passed-in attributes if set
        if (isset($attributes['colorTheme'])) {
            $this->colorTheme = $this->get_from_string_or_themecolor($attributes['colorTheme']);
        }

        // If no passed-in attribute (or applying it failed), check component defaults
        if (!isset($this->colorTheme)) {
            $class = static::class;
            $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
            $defaults = Config::getInstance()->get_component_defaults($classShortname);
            $this->colorTheme = isset($defaults['colorTheme'])
                ? $this->get_from_string_or_themecolor($defaults['colorTheme'])
                : null;
        }

        // Otherwise, use the passed-in default
        $this->colorTheme = $this->colorTheme ?? $default;
    }

    private function get_from_string_or_themecolor($value): ?ThemeColor {
        if ($value instanceof ThemeColor) {
            return $value;
        }
        else if (is_string($value)) {
            return ThemeColor::tryFrom($value);
        }

        return null;
    }
}
