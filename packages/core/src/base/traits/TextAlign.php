<?php
namespace Doubleedesign\Comet\Core;

trait TextAlign {
	/**
	 * @var Alignment|null $textAlign
	 */
	protected ?Alignment $textAlign = null;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	protected function set_text_align_from_attrs(array $attributes): void {
		$this->textAlign = isset($attributes['align']) ? Alignment::tryFrom($attributes['align']) : null;
	}
}
