<?php
namespace Doubleedesign\Comet\Core;

trait GroupLayoutType {
    /**
     * @var GroupLayout $layout
     * @description Layout style for grouping elements together.
     */
    protected GroupLayout $layout = GroupLayout::LIST;

    /**
     * @var int $maxPerRow
     * @description Maximum number of items to display per row in grid layouts.
     */
    protected int $maxPerRow = 3;

    /**
     * @param  array  $attributes
     * @param  ?GroupLayout  $default
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_group_layout_from_attrs(array $attributes, ?GroupLayout $default = null): void {
        $this->maxPerRow = isset($attributes['maxPerRow']) ? (int)$attributes['maxPerRow'] : $this->maxPerRow;

        if (isset($attributes['layout']) && $attributes['layout'] instanceof GroupLayout) {
            $this->layout = $attributes['layout'];

            return;
        }

        if (isset($attributes['layout']) || isset($attributes['groupLayout'])) {
            $layoutValue = $attributes['layout'] ?? $attributes['groupLayout'];
            $validatedLayout = GroupLayout::tryFrom($layoutValue);

            if ($validatedLayout !== null) {
                $this->layout = $validatedLayout;

                return;
            }
        }

        if ($default !== null) {
            $this->layout = $default;
        }
    }
}
