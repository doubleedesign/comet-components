<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ButtonGroup extends UIComponent {
	use LayoutOrientation;
	use LayoutAlignmentHorizontal;

	/**
	 * @var array<Button>
	 * @description Button objects to render inside the ButtonGroup
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ButtonGroup.button-group');
		$this->set_orientation_from_attrs($attributes);
		$this->set_halign_from_attrs($attributes);
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
