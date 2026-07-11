<?php
namespace Doubleedesign\Comet\Core;

class ThemeGradient {
    protected ?ThemeColor $from;
    protected ?ThemeColor $to;

    // Enable accessing the string value in the same way we do for the ThemeColor enum
    public string $value {
        get { return $this->__toString(); }
    }

    public function __construct(ThemeColor|string $from, ThemeColor|string $to) {
        $this->from = self::value_to_themecolor($from);
        $this->to = self::value_to_themecolor($to);
    }

    public static function tryFrom(?string $value): ?self {
        if (!$value) {
            return null;
        }

        $parts = explode('-', $value);
        if (count($parts) !== 2) {
            return null;
        }

        $from = self::value_to_themecolor($parts[0]);
        $to = self::value_to_themecolor($parts[1]);

        if (!$from || !$to) {
            return null;
        }

        return new self($from, $to);
    }

    private static function value_to_themecolor(ThemeColor|string $color): ?ThemeColor {
        if ($color instanceof ThemeColor) {
            return $color;
        }

        return ThemeColor::tryFrom($color) ?? null;
    }

    public function __toString(): string {
        if (!$this->from || !$this->to) {
            return '';
        }

        return "{$this->from->value}-{$this->to->value}";
    }
}
