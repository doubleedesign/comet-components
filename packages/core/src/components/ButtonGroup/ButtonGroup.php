<?php
namespace Doubleedesign\Comet\Core;

class ButtonGroup extends UIComponent {
	use HasAllowedTags;

	protected ?Alignment $hAlign = Alignment::START;
	protected ?Orientation $orientation = Orientation::HORIZONTAL;

	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ButtonGroup.button-group');
		$this->tagName = Tag::DIV;
		// In WordPress, some blocks have $attributes['theSetting'] and some have $attributes['layout']['theSetting'] so we need to account for both
		$hAlign = $attributes['justifyContent'] ?? $attributes['layout']['justifyContent'] ?? null;
		$this->hAlign = isset($hAlign) ? Alignment::fromString($hAlign) : null;
		$orientation = $attributes['orientation'] ?? $attributes['layout']['orientation'] ?? null;
		$this->orientation = isset($orientation) ? Orientation::tryFrom($orientation) : null;
	}

	function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->hAlign)) {
			$classes[] = $this->shortName . '--halign-' . $this->hAlign->value;

		}

		if (isset($this->orientation) && $this->orientation == Orientation::VERTICAL) {
			$classes[] = $this->shortName . '--stacked';
		}

		return $classes;
	}

	public function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();

	}
}
