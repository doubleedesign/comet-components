<?php
namespace Doubleedesign\Comet\Core;

trait Icon {
	/**
	 * @var string $iconPrefix
	 * @description Icon prefix class name
	 * @default-value fa-solid
	 */
	protected string $iconPrefix;

	/**
	 * @var ?string $icon
	 * @description Icon class name
	 */
	protected ?string $icon;

	public function set_icon_from_attrs(array $attributes): void {
		if(!isset($attributes['icon'])) return;

		$this->iconPrefix = $attributes['iconPrefix'] ?? CometConfig::get_icon_prefix();
		$this->icon = $attributes['icon'];
	}
}
