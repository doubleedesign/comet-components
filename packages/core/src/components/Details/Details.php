<?php
namespace Doubleedesign\Comet\Core;

class Details extends UIComponent {
	use HasAllowedTags;

	protected ?Tag $tagName = Tag::DETAILS;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DETAILS];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Details.details');
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
