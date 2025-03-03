<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ContentWrapper extends LayoutComponent {
	protected float $maxWidth = 50; // percentage
	protected float $overlayAmount = 0; // pixels

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ImageAndText.ContentWrapper.content-wrapper');
		$this->context = 'image-and-text';
		$this->shortName = 'content';
		$this->maxWidth = $attributes['maxWidth'] ?? $this->maxWidth;
		$this->overlayAmount = $attributes['overlayAmount'] ?? $this->overlayAmount;
	}

	function get_inline_styles(): array {
		$styles = parent::get_inline_styles();

		if($this->overlayAmount > 0) {
			$styles['--overlay-amount'] = "-{$this->overlayAmount}px";
		}

		return $styles;
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
