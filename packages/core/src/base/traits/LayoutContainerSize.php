<?php
namespace Doubleedesign\Comet\Core;

trait LayoutContainerSize {
    /**
     * @var ?ContainerSize $size
     * @description Keyword specifying the relative width of the container for the inner content if the component is not nested inside another layout component. Ignored if the component has an isNested attribute set to true, or other logic determines that it is not nested.
     * @default-value ContainerSize::DEFAULT
     */
    protected ?ContainerSize $size = ContainerSize::DEFAULT;

    /**
     * @param  array  $attributes
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    public function set_size_from_attrs(array $attributes): void {
        $this->size = $this->get_from_string_or_container_size($attributes['size'])
            ?? $this->get_component_default()
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
