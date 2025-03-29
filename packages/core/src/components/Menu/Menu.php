<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class Menu extends UIComponent {
	private array $rawMenuData;

	/**
	 * @param array $attributes
	 * @param array<MenuItem> $menuItems
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
	function __construct(array $attributes, array $menuItems) {
		$this->rawMenuData = $menuItems;
		$innerComponents = [
			new MenuList($attributes, $this->array_to_items($menuItems, $attributes['context'] ?? ''))
		];

		parent::__construct($attributes, $innerComponents, 'components.Menu.menu');
	}

	/**
	 * @param array $items
	 * @param string|null $context
	 * @return array<MenuListItem>
	 */
	private function array_to_items(array $items, ?string $context = ''): array {
		return array_map(function($item) use ($context) {
			$itemObject = new MenuListItem(
				[
					'id'              => $item['id'] ?? null,
					'classes'         => $item['classes'] ?? '',
					'context'         => $context ? "{$context}__menu-list" : 'menu-list',
					'isCurrentParent' => $item['isCurrentParent'] ?? 'false'
				],
				[
					new Link(
						array_merge(
							$item['link_attributes'] ?? [],
							['context' => $context ? "{$context}__menu-list__item" : 'menu-list__item']
						),
						$item['title']
					)
				]
			);

			// Handle nested lists
			if(!empty($item['children'])) {
				$itemObject->innerComponents[] = new MenuList(
					[],
					$this->array_to_items($item['children'])
				);
			}

			return $itemObject;
		}, $items);
	}

	/**
	 * Get the menu items as a plain array to pass to JavaScript
	 * @param array<MenuList>|null $components - initially null, used for recursion
	 * @return array
	 */
	public function get_raw_menu_data(array|null $components): array {
		$components = $components ?? $this->innerComponents;
		$list = array_filter($components, fn($component) => $component instanceof MenuList)[0]->innerComponents;
		if(!$list) return [];

		$transformed = array_map(function(MenuListItem $item) {
			// TODO: Replace with array_find in PHP 8.4
			/** @var Link $link */
			$link = array_filter($item->innerComponents, fn($component) => $component instanceof Link)[0];
			/** @var array<MenuList> $children */
			$children = array_values(array_filter($item->innerComponents, fn($component) => $component instanceof MenuList));
			if(!$link && !$children) return null;

			$linkAttrs = $link->get_html_attributes();
			$linkAttrs['classes'] = array_merge($link->get_filtered_classes());

			return [
				...$item->get_html_attributes(),
				'id'              => $item->get_id(),
				'title'           => $link->get_content(),
				'classes'         => array_merge($item->get_filtered_classes()),
				'link_attributes' => $linkAttrs,
				'children'        => !empty($children) ? $this->get_raw_menu_data($children) : []];
		}, $list);

		return $transformed;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
