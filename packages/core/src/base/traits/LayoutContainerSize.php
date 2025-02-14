<?php
namespace Doubleedesign\Comet\Core;

trait LayoutContainerSize {
	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;

	function set_size_from_attrs(array $attributes): void {
		if (isset($attributes['size'])) {
			$this->size = ContainerSize::tryFrom($attributes['size']);
		}
		else if (isset($attributes['className'])) {
			$this->size = ContainerSize::fromWordPressClassName($attributes['className']);
		}
	}
}
