<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ImageAndText extends UIComponent {
	use LayoutOrientation;

	/**
	 * @var array<ImageWrapper|ContentWrapper> $innerComponents
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		$this->set_orientation_from_attrs($attributes);
		parent::__construct($attributes, $innerComponents, 'components.ImageAndText.image-and-text');
	}

	function get_html_attributes(): array {
		return array_merge(
			parent::get_html_attributes(),
			['data-orientation' => $this->orientation->value]
		);
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
