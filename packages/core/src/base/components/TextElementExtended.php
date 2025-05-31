<?php

namespace Doubleedesign\Comet\Core;

abstract class TextElementExtended extends TextElement {
    use TextColor;

    public function __construct(array $attributes, string $content, string $bladeFile) {
        parent::__construct($attributes, $content, $bladeFile);
        $this->set_text_color_from_attrs($attributes);
    }
}
