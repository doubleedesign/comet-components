<?php
namespace Doubleedesign\Comet\Core;

trait LayoutAlignmentVertical {
	protected ?Alignment $vAlign = Alignment::START;

	/**
	 * @param array $attributes
	 * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
	 */
	protected function set_valign_from_attrs($attributes): void {
		// In WordPress, some blocks have $attributes['theSetting'] and some have $attributes['layout']['theSetting'] so we need to account for both
		$vAlign = $attributes['alignItems'] ?? $attributes['layout']['alignItems'] ?? null;
		$this->vAlign = isset($vAlign) ? Alignment::fromString($vAlign) : null;
	}
}
