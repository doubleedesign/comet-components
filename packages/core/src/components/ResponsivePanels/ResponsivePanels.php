<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanels extends PanelGroupComponent {
	use Icon;

	/**
	 * @var string $breakpoint
	 * @description The container breakpoint at which to switch between accordion and tabs
	 */
	protected string $breakpoint;

	/**
	 * @var ?string $icon
	 * @description Icon class name for the icon to use for the expand/collapse indicator in accordion mode
	 */
	protected ?string $icon;

	/** @var array<ResponsivePanel> */
	protected array $innerComponents;

	/**
	 * @var array
	 * @description Panels transformed for use by the Vue component.
	 */
	private array $panels = [];

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ResponsivePanels.responsive-panels');
		$this->breakpoint = $attributes['breakpoint'] ?? '768px';
		$this->set_icon_from_attrs($attributes, 'fa-plus');
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes(),
			'attributes' => $this->get_html_attributes(),
			'breakpoint' => $this->breakpoint,
			'panels'     => $this->get_panel_data_for_vue(),
			'icon'       => "$this->iconPrefix $this->icon"
		])->render();
	}
}
