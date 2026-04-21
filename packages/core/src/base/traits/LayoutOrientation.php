<?php
namespace Doubleedesign\Comet\Core;

trait LayoutOrientation {
    /**
     * @var Orientation|null $orientation
     * @description Orientation of the component content, if applicable
     * @default-value Orientation::VERTICAL
     */
    protected ?Orientation $orientation = Orientation::VERTICAL;

    /**
     * @param  array  $attributes
     * @param  Orientation|null  $default
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_orientation_from_attrs(array $attributes, ?Orientation $default = Orientation::VERTICAL): void {
        // Set from passed-in attributes if set
        if (isset($attributes['orientation'])) {
            $this->orientation = $this->get_from_string_or_orientation($attributes['orientation']);
        }

        // If no passed-in attribute (or applying it failed), check component defaults
        if (!isset($this->orientation)) {
            $class = static::class;
            $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
            $defaults = Config::getInstance()->get_component_defaults($classShortname);
            $this->orientation = isset($defaults['orientation'])
                ? $this->get_from_string_or_orientation($defaults['orientation'])
                : null;
        }

        // Otherwise, use the passed-in default if there is one
        $this->orientation = $this->orientation ?? $default;
    }

    private function get_from_string_or_orientation($value): ?Orientation {
        if ($value instanceof Orientation) {
            return $value;
        }
        else if (is_string($value)) {
            return Orientation::tryFrom($value);
        }

        return null;
    }
}
