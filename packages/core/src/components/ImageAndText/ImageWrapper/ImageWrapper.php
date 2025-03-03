<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ImageWrapper extends LayoutComponent {
	protected float $maxWidth = 100;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ImageAndText.ImageWrapper.image-wrapper');
		$this->context = 'image-and-text';
		$this->shortName = 'image';
		$this->maxWidth = $attributes['maxWidth'] ?? $this->maxWidth;
	}

	function get_inner_inline_styles(): string {
		$styleObj = array_merge(
			parent::get_inline_styles(),
			['max-width' => "$this->maxWidth%"],
			['flex-basis' => "$this->maxWidth%"],
		);

		return implode("; ", array_map(function($key, $value) {
			return "$key: $value";
		}, array_keys($styleObj), $styleObj));
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'     => $this->get_filtered_classes_string(),
			'attributes'  => $this->get_html_attributes(),
			'innerStyles' => $this->get_inner_inline_styles(),
			'children'    => $this->innerComponents
		])->render();
	}
}
