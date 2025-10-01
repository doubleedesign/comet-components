<?php
namespace Doubleedesign\Comet\Core;

trait LayoutContainerSize {
    /**
     * @var ?ContainerSize $size
     * @description Keyword specifying the relative width of the container for the inner content if the component is not nested inside another layout component. Ignored if the component has an isNested attribute set to true, or other logic determines that it is not nested.
     */
    protected ?ContainerSize $size = ContainerSize::DEFAULT;

    /**
     * @param  array  $attributes
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    public function set_size_from_attrs(array $attributes): void {
        if (isset($attributes['size'])) {
            if ($attributes['size'] instanceof ContainerSize) {
                $this->size = $attributes['size'];
            }
            else {
                $this->size = ContainerSize::tryFrom($attributes['size']);
            }
        }
        // Backwards compatibility with old WordPress implementation that used block styles instead of a proper attribute
        else if (isset($attributes['className'])) {
            $this->size = ContainerSize::from_wordpress_class_name($attributes['className']);
        }
    }
}
