<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Group extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Group.group');
		// Allow something other than "group" to be used as the shortName, primarily for automatic BEM class naming
		$this->shortName = $attributes['shortName'] ?? $this->shortName;
	}
}
