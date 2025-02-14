<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ButtonGroup extends UIComponent {
	protected ?Alignment $hAlign = Alignment::START;
	protected ?Orientation $orientation = Orientation::HORIZONTAL;

	/**
	 * @var array<Button>
	 * @description Button objects to render inside the ButtonGroup
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ButtonGroup.button-group');

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
