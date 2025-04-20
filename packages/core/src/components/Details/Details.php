<?php
namespace Doubleedesign\Comet\Core;

/**
 * Details component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description A component that renders a single expandable/collapsible panel.
 */
#[AllowedTags([Tag::DETAILS])]
#[DefaultTag(Tag::DETAILS)]
class Details extends UIComponent {

	/**
	 * @var string summary
	 * @description The title of the panel, summarising the content.
	 */
	protected string $summary;

	function __construct(array $attributes, array $innerComponents) {
		$this->summary = $attributes['summary'] ?? 'Details';
		parent::__construct($attributes, $innerComponents, 'components.Details.details');
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'summary'    => $this->summary,
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
