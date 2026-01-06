<?php
namespace Doubleedesign\Comet\Core;

enum GroupLayout: string {
    case LIST = 'list';
    case GRID = 'grid';
    case INLINE = 'inline';

    public static function fromString(?string $value): ?self {
        return match ($value) {
            'grid'   => self::GRID,
            'inline' => self::INLINE,
            default  => self::LIST
        };
    }

    public function isDefault(): bool {
        return $this === self::LIST;
    }
}
