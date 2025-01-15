<?php
namespace Doubleedesign\Comet\Core;
use InvalidArgumentException;

trait HasAllowedTags {
    abstract protected static function get_allowed_html_tags(): array;

    /**
     * @throws InvalidArgumentException
     */
    protected function validate_html_tag(Tag $tag): void {
        if (!in_array($tag, static::get_allowed_html_tags(), true)) {
            throw new InvalidArgumentException(sprintf(
                'Tag "%s" is not allowed for component "%s". Allowed tags are: %s',
                $tag->value,
                static::class,
                implode(', ', array_map(fn(Tag $t) => $t->value, static::get_allowed_html_tags()))
            ));
        }
    }
}
