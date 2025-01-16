<?php
namespace Doubleedesign\Comet\Core;

class LatestPosts extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.LatestPosts.latest-posts');
	}
}
