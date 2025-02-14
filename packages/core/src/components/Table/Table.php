<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::FIGURE])]
#[DefaultTag(Tag::FIGURE)]
class Table extends Renderable {
	function __construct(array $attributes, array $data) {
		parent::__construct($attributes, 'components.Table.table');
	}

	#[NotImplemented]
	function render(): void {
	}
}
