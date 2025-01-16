<?php
namespace Doubleedesign\Comet\Core;

trait HasAllowedTags {
	abstract protected static function get_allowed_wrapping_tags(): array;

	protected function validate_html_tag(Tag $tag): bool {
		return in_array($tag, static::get_allowed_wrapping_tags(), true);
	}
}
