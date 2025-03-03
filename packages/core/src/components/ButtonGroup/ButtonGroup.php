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
		$this->set_orientation_from_attrs($attributes, Orientation::HORIZONTAL);
		$this->set_halign_from_attrs($attributes);
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->orientation)) {
			$attributes['data-orientation'] = $this->orientation->value;
		}

		if(isset($this->hAlign)) {
			$attributes['data-halign'] = $this->hAlign->value;
		}

		if(isset($this->vAlign)) {
			$attributes['data-valign'] = $this->vAlign->value;
		}

		return $attributes;
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
