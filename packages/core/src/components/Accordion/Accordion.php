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

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
		$this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
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
			'children'   => $this->innerComponents
		])->render();
	}
}
