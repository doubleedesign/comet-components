<?php
namespace Doubleedesign\Comet\Core;

/**
 * Menu component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a navigation menu that can have multiple levels of lists.
 */
#[AllowedTags([Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class Menu extends UIComponent {
    use ColorTheme;
    private array $rawMenuData;

    /**
     * @param  array  $attributes
     * @param  array<MenuItem>  $menuItems
     *
     * @phpstan-type MenuItem array{
     *   id: int | string,
     *   title: string,
     *   link_attributes: array{
     *     href: string,
     *     target?: string,
     *     'aria-current'?: string
     *   },
     *   children: array<MenuItem>
     * }
     */
    public function __construct(array $attributes, array $menuItems) {
        $this->rawMenuData = $menuItems;
        $this->set_color_theme($attributes);
        $innerComponents = [new MenuList($attributes, $this->array_to_items($menuItems, 1))];

        parent::__construct($attributes, $innerComponents, 'components.Menu.menu');
    }

    /**
     * @param  array  $items
     * @param  int  $level
     *
     * @return array<MenuListItem>
     */
    private function array_to_items(array $items, int $level): array {
        return array_map(function($item) use ($level) {
            $itemObject = new MenuListItem(
                [
                    'id'              => $item['id'] ?? null,
                    'classes'         => $item['classes'] ?? '',
                    'isCurrentParent' => $item['isCurrentParent'] ?? 'false',
                ],
                [$this->get_item_content_component($item)]
            );

            // Handle nested lists
            if (!empty($item['children'])) {
                $submenu = new MenuList(
                    ['shortName' => 'sub-menu'],
                    $this->array_to_items($item['children'], $level + 1)
                );

                $itemObject->innerComponents[] = $submenu;
            }

            return $itemObject;
        }, $items);
    }

    private function get_item_content_component($item): Button|Link {
        if (isset($item['link_attributes']['target']) && $item['link_attributes']['target'] === '_blank') {
            return new Button(
                array_merge(
                    $item['link_attributes'] ?? [],
                    ['classes'    => ['button']],
                ),
                $item['title']
            );
        }

        if (isset($item['link_attributes']['classes']) && in_array('button', $item['link_attributes']['classes'])) {
            return new Button(
                $item['link_attributes'],
                $item['title']
            );
        }

        return new Link(array_merge($item['link_attributes'], ['label' => $item['title']]));
    }

    /**
     * Get the menu items as a plain array to pass to JavaScript
     *
     * @param  array<MenuList>|null  $components  - initially null, used for recursion
     *
     * @return array
     */
    public function get_raw_menu_data(?array $components): array {
        $components = $components ?? $this->innerComponents;
        $list = array_filter($components, fn($component) => $component instanceof MenuList)[0]->innerComponents;
        if (!$list) {
            return [];
        }

        return array_map(function(MenuListItem $item) {
            /** @var Link $link */
            $link = array_find($item->innerComponents, fn($component) => $component instanceof Link || $component instanceof Button);

            /** @var array<MenuList> $children */
            $children = array_values(array_filter($item->innerComponents, fn($component) => $component instanceof MenuList));
            if (!$link && !$children) {
                return null;
            }

            $linkAttrs = $link->get_html_attributes();
            $linkAttrs['classes'] = array_merge($link->get_filtered_classes());

            return [
                ...$item->get_html_attributes(),
                // if there is no explicit ID, only generate one if there is a submenu (for aria attributes)
                'id'              => $item->get_id(!empty($children)),
                'title'           => $link->get_content(),
                'classes'         => $item->get_filtered_classes(),
                'link_attributes' => $linkAttrs,
                'children'        => !empty($children) ? $this->get_raw_menu_data($children) : []
            ];
        }, $list);
    }

    /**
     * Get the color theme for this menu, if set, to pass to JavaScript
     *
     * @return ThemeColor|null
     */
    public function get_color_theme(): ?ThemeColor {
        return $this->colorTheme;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
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
