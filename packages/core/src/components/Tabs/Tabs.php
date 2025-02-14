<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Tabs extends UIComponent {
	/**
	 * @var array<TabPanel>
	 * @description Wrapping components each containing a TabPanelTitle and TabPanelContent.
	 */
	protected array $innerComponents;

	protected ?Orientation $orientation = Orientation::HORIZONTAL;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Tabs.tabs');
		$this->orientation = isset($attributes['orientation']) ? Orientation::tryFrom($attributes['orientation']) : Orientation::HORIZONTAL;
		$this->build_tablist();
	}

	function get_html_attributes(): array {
		return array_merge(
			parent::get_html_attributes(),
			[
				'data-orientation' => $this->orientation->value
			]
		);
	}

	/**
	 * Collect the TabPanelTitles from the TabPanels
	 * and add them as a TabList that is the first element of the innerComponents
	 * @return void
	 */
	function build_tablist(): void {
		$panels = $this->innerComponents;
		$titles = array_reduce($panels, function ($acc, $panel) {
			if ($panel instanceof TabPanel) {
				$acc[] = $panel->get_title_component();
			}
			return $acc;
		}, []);

		$attributes = [
			'tagName' => Tag::UL->value, // TODO: Implement <ol> option
		];

		$this->innerComponents = [
			new TabList($attributes, $titles),
			...$panels
		];
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
