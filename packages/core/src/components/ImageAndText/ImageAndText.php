<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ImageAndText extends UIComponent {
	/**
	 * @var array<ImageWrapper|ContentWrapper> $innerComponents
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ImageAndText.image-and-text');
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
