<?php
namespace Doubleedesign\Comet\Core;

class AccordionPanel extends UIComponent {
	use HasAllowedTags;
	/** @var array<AccordionPanelTitle|AccordionPanelContent> */
	protected array $innerComponents;

	/**
	 * @var ?Tag $tagName
	 * @description The HTML tag to use for this component
	 */
	protected ?Tag $tagName = Tag::DETAILS;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DETAILS];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'accordion']),
			$innerComponents,
			'components.Accordion.AccordionPanel.accordion-panel'
		);
		$this->tagName = Tag::DETAILS;
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
