<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class Menu extends UIComponent {
	/**
	 * @param array $attributes
	 * @param array<MenuItem> $menuItems
	 *
	 * @phpstan-type MenuItem array{
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
					'classes' => $item['classes'] ?? '',
					'context' => $context ? "{$context}__menu-list" : 'menu-list'
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

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
