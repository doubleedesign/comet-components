<?php

namespace Doubleedesign\Comet\Core;

trait Icon {
    /**
     * @description Icon prefix class name
     * @default-value fa-solid
     */
    protected ?string $iconPrefix;

    /**
     * @description Icon class name
     */
    protected ?string $icon = null;

    protected function set_icon_from_attrs(array $attributes, ?string $default = null): void {
        if (!isset($attributes['icon']) && $default === null) {
            return;
        }

        $this->iconPrefix = $attributes['iconPrefix'] ?? Config::get_icon_prefix();
        $this->icon = $attributes['icon'] ?? $default ?? null;
    }
}
