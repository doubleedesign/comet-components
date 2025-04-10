<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Tabs extends UIComponent {
	use ColorTheme;
	use LayoutOrientation;

	/**
	 * @var array<TabPanel>
	 * @description Wrapping components each containing a TabPanelTitle and TabPanelContent.
	 */
	protected array $innerComponents;

	/**
	 * @var array
	 * @description Panels transformed for use by the Vue component.
	 */
	private array $panels = [];

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Tabs.tabs');
		$this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
		$this->set_orientation_from_attrs($attributes);
		$this->prepare_inner_components_for_vue();
	}

	private function prepare_inner_components_for_vue(): void {
		foreach($this->innerComponents as $panel) {
			if(!$panel instanceof TabPanel) {
				error_log('Tabs: Invalid inner component type found and ignored.');
			}

			$this->panels[] = [
				'title' => $panel->get_title(),
				'content' => $panel->get_content(),
			];
		}
	}

	function get_html_attributes(): array {
		return array_merge(
			parent::get_html_attributes(),
			['data-orientation' => $this->orientation->value],
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'panels'	 => $this->panels,
		])->render();
	}
}
