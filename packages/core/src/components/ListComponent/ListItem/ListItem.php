<?php
namespace Doubleedesign\Comet\Core;

class ListItem {
	protected ?Tag $tagName = Tag::LI;
	private ListItemSimple|ListItemComplex $instance;

	protected ?string $content;
	protected ?array $innerComponents;

	function __construct(array $attributes, string $content, array $nestedLists = []) {
		$attributes['tagName'] = 'li';
		$this->innerComponents = $nestedLists;
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
