<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Accordion extends UIComponent {
	use ColorTheme;

	/**
	 * @var array<AccordionPanel>
	 * @description Wrapping components each containing an AccordionPanelTitle and AccordionPanelContent.
	 */
	protected array $innerComponents;

	/**
	 * @var array
	 * @description Panels transformed for use by the Vue component.
	 */
	private array $panels = [];

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
		$this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
		$this->prepare_inner_components_for_vue();
	}

	private function prepare_inner_components_for_vue(): void {
		foreach($this->innerComponents as $panel) {
			if(!$panel instanceof AccordionPanel) {
				error_log('Accordion: Invalid inner component type found and ignored.');
			}

			$this->panels[] = [
				'title' => $panel->get_title(),
				'content' => $panel->get_content(),
			];
		}
	}

	function get_html_attributes(): array {
		$attrs = parent::get_html_attributes();

		if($this->colorTheme) {
			$attrs['data-color-theme'] = $this->colorTheme->value;
		}

		return $attrs;
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
