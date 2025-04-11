<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Accordion extends PanelGroupComponent {
	use Icon;

	/**
	 * @var ?string $icon
	 * @description Icon class name for the icon to use for the expand/collapse indicator.
	 */
	protected ?string $icon;

	/** @var array<AccordionPanel> */
	protected array $innerComponents;


	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
		$this->set_icon_from_attrs($attributes, 'fa-plus');
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'panels'     => $this->get_panel_data_for_vue(),
			'icon'       => "$this->iconPrefix $this->icon"
		])->render();
	}
}
