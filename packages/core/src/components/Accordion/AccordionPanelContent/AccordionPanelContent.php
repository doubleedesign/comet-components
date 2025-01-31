<?php
namespace Doubleedesign\Comet\Core;

class AccordionPanelContent extends UIComponent {
	use HasAllowedTags;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'accordion__panel']),
			$innerComponents,
			'components.Accordion.AccordionPanelContent.accordion-panel-content'
		);
	}

	function get_inline_styles(): array {
		return [];
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
