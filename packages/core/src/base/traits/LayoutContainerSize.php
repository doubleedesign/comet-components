<?php

namespace Doubleedesign\Comet\Core;

trait LayoutContainerSize {
    /**
     * @description Keyword specifying the relative width of the container for the inner content
     */
    protected ?ContainerSize $size = ContainerSize::DEFAULT;

    /**
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    public function set_size_from_attrs(array $attributes): void {
        if (isset($attributes['size'])) {
            $this->size = ContainerSize::tryFrom($attributes['size']);
        }
        // Backwards compatibility with old WordPress implementation that used block styles instead of a proper attribute
        elseif (isset($attributes['className'])) {
            $this->size = ContainerSize::from_wordpress_class_name($attributes['className']);
        }

        if ($this->size !== null) {
            $this->add_attributes(['size' => $this->size->value]);
        }
    }
}
