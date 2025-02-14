<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class Menu extends UIComponent {
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
			if(isset($item['children'])) {
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
