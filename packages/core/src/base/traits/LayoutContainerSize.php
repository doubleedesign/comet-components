<?php
namespace Doubleedesign\Comet\Core;

trait LayoutContainerSize {
	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	function set_size_from_attrs(array $attributes): void {
		if (isset($attributes['size'])) {
			$this->size = ContainerSize::tryFrom($attributes['size']);
		}
		else if (isset($attributes['className'])) {
			$this->size = ContainerSize::fromWordPressClassName($attributes['className']);
		}
	}
}
