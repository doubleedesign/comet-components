<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanels extends UIComponent {
	use ColorTheme;
	use LayoutOrientation;

	/**
	 * @var string $breakpoint
	 * @description The container breakpoint at which to switch between accordion and tabs
	 */
	protected string $breakpoint;

	/**
	 * @var array<ResponsivePanel> $innerComponents
	 */
	protected array $innerComponents;

	private array $titles = [];
	private array $panels = [];

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ResponsivePanels.responsive-panels');
		$this->set_orientation_from_attrs($attributes);
		$this->set_color_theme_from_attrs($attributes);
		$this->breakpoint = $attributes['breakpoint'] ?? '768px';
		$this->prepare_inner_components_for_vue();
	}

	private function prepare_inner_components_for_vue(): void {
		foreach($this->innerComponents as $panel) {
			if(!$panel instanceof ResponsivePanel) {
				error_log('ResponsivePanels: Invalid inner component type found and ignored.');
			}

			array_push($this->titles, $panel->get_title());
			array_push($this->panels, $panel->get_content());
		}
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->orientation)) {
			$attributes['data-orientation'] = $this->orientation->value;
		}

		if(isset($this->colorTheme)) {
			$attributes['data-color-theme'] = $this->colorTheme->value;
		}

		return $attributes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes(),
			'attributes' => $this->get_html_attributes(),
			'breakpoint' => $this->breakpoint,
			'titles'     => $this->titles,
			'panels'     => $this->panels
		])->render();
	}
}
