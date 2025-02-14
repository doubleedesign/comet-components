<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV])]
#[DefaultTag(Tag::SECTION)]
class Container extends UIComponent {

	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;
	/**
	 * @var ?ThemeColor $background_color
	 * @description Background colour keyword
	 */
	protected ?ThemeColor $backgroundColor;
	
	function __construct(array $attributes, array $innerComponents) {
		if (isset($attributes['size'])) {
			$this->size = ContainerSize::tryFrom($attributes['size']);
		}
		else if (isset($attributes['className'])) {
			$this->size = ContainerSize::fromWordPressClassName($attributes['className']);
		}

		$this->backgroundColor = isset($attributes['backgroundColor']) ? ThemeColor::tryFrom($attributes['backgroundColor']) : null;

		parent::__construct($attributes, $innerComponents, 'components.Container.container');
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
