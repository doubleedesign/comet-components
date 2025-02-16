<?php
namespace Doubleedesign\Comet\Core;

trait LayoutAlignmentHorizontal {
	protected ?Alignment $hAlign = Alignment::START;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	protected function set_halign_from_attrs($attributes): void {
		// In WordPress, some blocks have $attributes['theSetting'] and some have $attributes['layout']['theSetting'] so we need to account for both
		$hAlign = $attributes['justifyContent'] ?? $attributes['layout']['justifyContent'] ?? null;
		$this->hAlign = isset($hAlign) ? Alignment::fromString($hAlign) : null;
	}
}
