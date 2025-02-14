<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV])]
#[DefaultTag(Tag::SECTION)]
class Container extends UIComponent {
	use LayoutContainerSize;
	use BackgroundColor;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Container.container');
		$this->set_size_from_attrs($attributes);
	}

	protected function get_filtered_classes(): array {
		$classes = array_filter(parent::get_filtered_classes(), function ($class) {
			return !in_array($class, ['is-style-wide', 'is-style-fullwidth', 'is-style-narrow']);
		});

		if ($this->size !== ContainerSize::DEFAULT) {
			array_push($classes, "container--{$this->size->value}");
		}

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		return $classes;
	}


	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
