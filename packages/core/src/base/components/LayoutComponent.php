<?php
namespace Doubleedesign\Comet\Core;

abstract class LayoutComponent extends UIComponent {
	use BackgroundColor;
	use LayoutAlignmentHorizontal;
	use LayoutAlignmentVertical;

	function __construct(array $attributes, array $children, string $bladeFile) {
		parent::__construct($attributes, $children, $bladeFile);
		$this->set_background_color_from_attrs($attributes);
		$this->set_halign_from_attrs($attributes);
		$this->set_valign_from_attrs($attributes);
	}
	
	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->hAlign)) {
			$attributes['data-halign'] = $this->hAlign->value;
		}

		if(isset($this->vAlign)) {
			$attributes['data-valign'] = $this->vAlign->value;
		}

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}

		return $attributes;
	}

	/**
	 * Default render method (child classes may override this)
	 *
	 * @return void
	 */
	public function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
