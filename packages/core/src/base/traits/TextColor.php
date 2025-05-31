<?php

namespace Doubleedesign\Comet\Core;

trait TextColor {
    protected ?ThemeColor $textColor = null;

    /**
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_text_color_from_attrs(array $attributes): void {
        $this->textColor = isset($attributes['textColor'])
            ? ThemeColor::tryFrom($attributes['textColor'])
            : null;

        if ($this->textColor !== null) {
            $this->add_attributes(['data-text-color' => $this->textColor->value]);
        }
    }
}
