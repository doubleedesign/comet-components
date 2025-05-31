<?php

namespace Doubleedesign\Comet\Core;

trait TextAlign {
    protected ?Alignment $textAlign = null;

    /**
     * @description Retrieves the relevant properties from the component $attributes array, validates them, and assigns them to the corresponding component instance field.
     */
    protected function set_text_align_from_attrs(array $attributes): void {
        if (isset($attributes['align'])) {
            $this->textAlign = Alignment::fromString($attributes['align']) ?? null;
        }
        else if (isset($attributes['textAlign'])) {
            $this->textAlign = Alignment::fromString($attributes['textAlign']) ?? null;
        }
    }
}
