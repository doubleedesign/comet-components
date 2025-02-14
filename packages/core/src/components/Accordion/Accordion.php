<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Accordion extends UIComponent {
	use ColorTheme;

	/** @var array<AccordionPanel> */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Accordion.accordion');
		$this->set_color_theme_from_attrs($attributes);
	}

	function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		$result = array_merge(
			$classes,
			["{$this->get_bem_name()}--color-theme-{$this->colorTheme->value}"],
		);

		return array_unique($result);
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
