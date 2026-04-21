<?php
namespace Doubleedesign\Comet\Core;

trait GroupLayoutType {
    /**
     * @var GroupLayout $layout
     * @description Layout style for grouping elements together.
     */
    protected GroupLayout $layout;

    /**
     * @var int $maxPerRow
     * @description Maximum number of items to display per row in grid layouts.
     */
    protected int $maxPerRow = 3;

    /**
     * @param  array  $attributes
     * @param  ?GroupLayout  $fallback
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_group_layout_from_attrs(array $attributes, ?GroupLayout $fallback = GroupLayout::LIST): void {
        $this->maxPerRow = isset($attributes['maxPerRow']) ? (int)$attributes['maxPerRow'] : $this->maxPerRow;

        // Set layout from passed-in attributes if set
        if (isset($attributes['layout'])) {
            $this->layout = $this->get_from_string_or_group_layout($attributes['layout']);
        }

        // If no passed-in attribute (or applying it failed), check component defaults
        if (!isset($this->layout)) {
            $class = static::class;
            $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
            $defaults = Config::getInstance()->get_component_defaults($classShortname);
            if (isset($defaults['layout'])) {
                $this->layout = $this->get_from_string_or_group_layout($defaults['layout']);
            }
        }

        // Otherwise, use the fallback
        $this->layout = $this->layout ?? $fallback;
    }

    private function get_from_string_or_group_layout($value): ?GroupLayout {
        if ($value instanceof GroupLayout) {
            return $value;
        }
        else if (is_string($value)) {
            return GroupLayout::tryFrom($value);
        }

        return null;
    }
}
