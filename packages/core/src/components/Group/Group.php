<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Group extends LayoutComponent {
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Group.group');
	}
}
