<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class CallToAction extends UIComponent {
	use BackgroundColor;

	/**
	 * @param array $attributes
	 * @param array<Heading|Paragraph|ButtonGroup> $innerComponents
	 */
	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.CallToAction.call-to-action');
		$this->set_background_color_from_attrs($attributes);
	}

	protected function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		return $classes;
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
