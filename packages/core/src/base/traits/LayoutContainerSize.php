<?php
namespace Doubleedesign\Comet\Core;

trait LayoutContainerSize {
    /**
     * @var ?ContainerSize $size
     * @description Keyword specifying the relative width of the container for the inner content
     *              if the component is not nested inside another layout component.
     *              Should be ignored if the component has an isNested attribute set to true, or other logic determines that it is nested.
     * @default-value ContainerSize::DEFAULT
     */
    protected ?ContainerSize $size = ContainerSize::DEFAULT;

    /**
     * @param  array|null  $attributes
     * @description Retrieves the relevant properties from the component $attributes array,
     *              validates them, and assigns the size value if the conditions are met to use it.
     */
    public function set_size(?array $attributes): void {
        if ($attributes === null) {
            $this->size = null;

            return;
        }

        if (method_exists($this, 'get_is_nested') && $this->get_is_nested()) {
            $this->size = null;

            return;
        }

        if (isset($attributes['isNested']) && $attributes['isNested'] === true) {
            $this->size = null;

            return;
        }

        $this->size = $this->get_from_string_or_container_size($attributes['size'] ?? '')
            ?? $this->get_from_string_or_container_size($this->get_component_default())
            ?? $this->size;
    }

    private function get_component_default(): ?ContainerSize {
        $class = static::class;
        $classShortname = Utils::kebab_case(substr($class, strrpos($class, '\\') + 1));
        $defaults = Config::getInstance()->get_component_defaults($classShortname);

        return isset($defaults['size']) ? $this->get_from_string_or_container_size($defaults['size']) : null;
    }

    private function get_from_string_or_container_size($value): ?ContainerSize {
        if ($value instanceof ContainerSize) {
            return $value;
        }
        else if (is_string($value)) {
            return ContainerSize::tryFrom($value);
        }

        return null;
    }
}
