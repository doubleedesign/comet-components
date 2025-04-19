<?php
namespace Doubleedesign\Comet\Core;

/**
 * Call-To-Action component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Highlight important information and prompt the user to take action.
 */
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

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}

		return $attributes;
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
