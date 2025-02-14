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

	/**
	 * Add classes for the relevant attributes
	 * @return array|string[]
	 */
	protected function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		if (isset($this->hAlign)) {
			$classes[] = $this->shortName . '--halign-' . $this->hAlign->value;
		}

		if (isset($this->vAlign)) {
			$classes[] = $this->shortName . '--valign-' . $this->vAlign->value;
		}

		return $classes;
	}


	/**
	 * Build the inline styles (style attribute) as a single string
	 * using the relevant supported attributes
	 * @return array<string, string>
	 */
	protected function get_inline_styles(): array {
		return [];
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
