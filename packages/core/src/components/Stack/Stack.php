<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Stack extends UIComponent {
	use BackgroundColor;
	use LayoutAlignmentHorizontal;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Stack.stack');
		$this->set_background_color_from_attrs($attributes);
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

		return $classes;
	}

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
