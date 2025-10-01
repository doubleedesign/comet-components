<?php
namespace Doubleedesign\Comet\Core;

enum Orientation: string {
    case HORIZONTAL = 'horizontal';
    case VERTICAL = 'vertical';

    public function isDefault(): bool {
        return $this === self::VERTICAL;
    }
}
