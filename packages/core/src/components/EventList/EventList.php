<?php
namespace Doubleedesign\Comet\Core;

/**
 * EventList component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class EventList extends CardList {

    public function __construct(array $attributes, array $innerComponents) {
        $attributes['shortName'] = $attributes['shortName'] ?? 'events';
        $attributes['link'] = [
            'href' => $attributes['viewAllUrl'] ?? null
        ];

        parent::__construct($attributes, $innerComponents);
    }

    protected function get_inner_group_attributes(): array {
        return array_merge(
            parent::get_inner_group_attributes(),
            ['tagName'  => 'ul']
        );
    }
}
