<?php
namespace Doubleedesign\Comet\Core;

trait LayoutAlignment {
	protected ?Alignment $hAlign = Alignment::START;
	protected ?Alignment $vAlign = Alignment::START;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	protected function set_layout_alignment_from_attrs(array $attributes): void {
		// In WordPress, some blocks have $attributes['theSetting'] and some have $attributes['layout']['theSetting'] so we need to account for both
		// Also different blocks have different attributes for vertical  alignment that we need to handle

		$hAlign = $attributes['justifyContent'] ?? $attributes['layout']['justifyContent'] ?? null;
		$this->hAlign = isset($hAlign) ? Alignment::fromString($hAlign) : null;

		$vAlign = $attributes['alignItems'] ?? $attributes['layout']['alignItems'] ?? $attributes['verticalAlignment'] ?? null;
		$this->vAlign = isset($vAlign) ? Alignment::fromString($vAlign) : null;
	}
}
