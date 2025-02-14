<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DETAILS])]
#[DefaultTag(Tag::DETAILS)]
class Details extends UIComponent {

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
