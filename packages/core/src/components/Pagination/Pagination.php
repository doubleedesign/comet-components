<?php
namespace Doubleedesign\Comet\Core;

/**
 * Pagination component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a pagination navigation list.
 */
#[AllowedTags([Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class Pagination extends UIComponent {
    use ColorTheme;

    /**
     * @param  array  $attributes
     * @param  array  $links  Indexed array of pagination link associative arrays with title, URL, and optional boolean 'current' for if this link is the current page
     */
    public function __construct(array $attributes, array $links) {
        $listItems = array_map(function($item) {
            $isPrev = $item['title'] === 'Previous';
            $isNext = $item['title'] === 'Next';

            return new Button([
                'tagName'   => Tag::A,
                'href'      => $item['href'] ?? $item['url'] ?? '#',
                'isOutline' => !$item['current'],
                ...($item['current'] ? ['aria-current' => 'page'] : []),
                'aria-label' => is_numeric($item['title']) ? "Page {$item['title']}" : (in_array($item['title'], ['Previous', 'Next']) ? "{$item['title']} page" : ""),
                'iconBefore' => $isPrev ? 'fa-arrow-left' : null,
                'iconAfter'  => $isNext ? 'fa-arrow-right' : null,
            ], $item['title']);
        }, $links);

        $buttonGroup = new ButtonGroup([
            'colorTheme'  => $attributes['colorTheme'] ?? ThemeColor::DARK,
            'orientation' => Orientation::HORIZONTAL,
            'hAlign'      => Alignment::CENTER
        ], $listItems);

        parent::__construct($attributes, [$buttonGroup], 'components.Pagination.pagination');
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
