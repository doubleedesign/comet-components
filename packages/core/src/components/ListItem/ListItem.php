<?php
namespace Doubleedesign\Comet\Core;

class ListItem {
	protected ?Tag $tag = Tag::LI;
	private ListItemSimple|ListItemComplex $instance;

	function __construct(array $attributes, string $content, array $nestedLists = []) {
		$attributes['tagName'] = 'li';
		if (!empty($nestedLists)) {
			$this->instance = new ListItemComplex($attributes, $content, $nestedLists);
		}
		else {
			$this->instance = new ListItemSimple($attributes, $content);
		}
	}

	public function render(): void {
		$this->instance->render();
	}
}
