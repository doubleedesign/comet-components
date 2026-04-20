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
        $this->set_color_theme_from_attrs($attributes);

        parent::__construct($attributes, [], 'components.Menu.menu');
        $this->innerComponents = [
            new MenuList($attributes, $this->array_to_items($menuItems, 1, $this->get_context()))
        ];
    }

    /**
     * @param  array  $items
     * @param  int  $level
     * @param  string|null  $context
     *
     * @return array<MenuListItem>
     */
    private function array_to_items(array $items, int $level, ?string $context = ''): array {
        return array_map(function($item) use ($level, $context) {
            $itemObject = new MenuListItem(
                [
                    'id'              => $item['id'] ?? null,
                    'classes'         => $item['classes'] ?? '',
                    'isCurrentParent' => $item['isCurrentParent'] ?? 'false',
                    'context'         => $context,
                    'shortName'       => 'item'
                ],
                [
                    $item['link_attributes']['target'] === '_blank'
                        ? new Button(
                            array_merge(
                                $item['link_attributes'] ?? [],
                                [
                                    'colorTheme' => 'primary',
                                    'classes'    => ['button'],
                                    'context'    => $level > 1 && $context
                                    ? "{$context}__item"
                                    : ($context ? "{$context}__menu__list__item" : 'menu-list__item')
                                ],
                            ),
                            $item['title']
                        )
                        : new Link([
                            ...$item['link_attributes'],
                            'label' => $item['title'],
                            ...(['context' => $level > 1 && $context
                                ? "{$context}__item"
                                : ($context ? "{$context}__menu__list__item" : 'menu-list__item')
                            ])
                        ])
                ]
            );

            if ($level > 1) {
                $itemObject->set_bem_block($context);
                $itemObject->update_context($context, true);
            }

            // Handle nested lists
            if (!empty($item['children'])) {
                $itemObject->innerComponents[] = new MenuList(
                    ['context' => $context, 'shortName' => 'sub-menu'],
                    $this->array_to_items($item['children'], $level + 1, "{$context}__menu__sub-menu")
                );
            }

            return $itemObject;
        }, $items);
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

        $transformed = array_map(function(MenuListItem $item) {
            // TODO: Replace with array_find in PHP 8.4
            /** @var Link $link */
            $link = array_filter($item->innerComponents, fn($component) => $component instanceof Link || $component instanceof Button)[0];
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
                'children'        => !empty($children) ? $this->get_raw_menu_data($children) : []];
        }, $list);

        return $transformed;
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
