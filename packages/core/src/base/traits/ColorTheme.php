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
        }

        if (isset($attributes['colorTheme']) && $attributes['colorTheme'] instanceof ThemeColor) {
            $this->colorTheme = $attributes['colorTheme'];

            return;
        }

        $this->colorTheme = isset($attributes['colorTheme'])
            ? ThemeColor::tryFrom($attributes['colorTheme'])
            : $default;
    }
}
