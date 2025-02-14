<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class TabPanel extends UIComponent {
	/** @var array<TabPanelTitle|TabPanelContent> */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs']),
			$innerComponents,
			'components.Tabs.TabPanel.tab-panel'
		);

		// Ensure the ID matches the anchor of the associated button
		// This will override any existing ID attribute
		$this->id = $this->get_title_component()->get_anchor();
	}

	/**
	 * Get the title component of the TabPanel
	 * Used by the parent Tabs component to collect all panel title components to render as a group
	 * @return TabPanelTitle|null
	 */
	function get_title_component(): ?TabPanelTitle {
		// TODO: When PHP 8.4 is available, this can be replaced with array_find because we only expect one
		return array_filter($this->innerComponents, fn($item) => $item instanceof TabPanelTitle)[0] ?? null;
	}

	/**
	 * The render function should only render the content, as the titles are taken care of by the parent Tabs component
	 * @return Array<TabPanelContent>
	 */
	function filtered_inner_components(): array {
		return array_filter($this->innerComponents, fn($component) => $component instanceof TabPanelContent);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->filtered_inner_components()
		])->render();
	}
}
