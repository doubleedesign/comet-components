<?php
namespace Doubleedesign\Comet\Core;

abstract class LayoutComponent extends UIComponent {
	use BackgroundColor;
	use LayoutAlignment;

	function __construct(array $attributes, array $innerComponents, string $bladeFile) {
		parent::__construct($attributes, $innerComponents, $bladeFile);
		$this->set_background_color_from_attrs($attributes);
		$this->set_layout_alignment_from_attrs($attributes);

		if(!$this->exclude_from_background_simplification()) {
			if($this instanceof Container && !$this->withWrapper) {
				$this->remove_redundant_background_colors();
			}
			else {
				$this->simplify_all_background_colors();
			}
		}
	}

	protected function get_filtered_classes(): array {
		if((!$this instanceof Column) && (!$this instanceof ContentWrapper) && (!$this instanceof ImageWrapper) && (!$this instanceof Steps)) {
			return array_merge(
				parent::get_filtered_classes(),
				['layout-block']
			);
		}

		return parent::get_filtered_classes();
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->hAlign) && !$this->hAlign->isDefault()) {
			$attributes['data-halign'] = $this->hAlign->value;
		}

		if(isset($this->vAlign) && !$this->vAlign->isDefault()) {
			$attributes['data-valign'] = $this->vAlign->value;
		}

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}
		else if(isset($this->gradient)) {
			$attributes['data-background'] = 'gradient-' . $this->gradient;
		}

		return $attributes;
	}

	private function exclude_from_background_simplification(): bool {
		foreach($this->innerComponents as $component) {
			if($component instanceof CallToAction) {
				return true;
			}
		}

		return false;
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
