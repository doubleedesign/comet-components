<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class ListItem {
    private ListItemSimple|ListItemComplex $instance;
    protected ?string $content;
    protected ?array $innerComponents;

    public function __construct(array $attributes, string $content, array $nestedLists = []) {
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
