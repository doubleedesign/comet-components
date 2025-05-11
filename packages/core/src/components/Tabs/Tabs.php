<?php
namespace Doubleedesign\Comet\Core;

/**
 * Tabs component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display grouped content in a tabbed interface.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Tabs extends PanelGroupComponent {

	/** @var array<TabPanel> */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Tabs.tabs');
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'panels'     => $this->get_panels(),
		])->render();
	}
}
