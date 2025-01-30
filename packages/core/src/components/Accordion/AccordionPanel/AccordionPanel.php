<?php
namespace Doubleedesign\Comet\Core;
use Exception;

class AccordionPanel extends UIComponent {
	use HasAllowedTags;

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
		$attrs = $this->get_html_attributes();

		try {
			echo $blade->make($this->bladeFile, [
				'classes'    => $this->get_filtered_classes_string(),
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e->getMessage(), true));
		}
	}
}
