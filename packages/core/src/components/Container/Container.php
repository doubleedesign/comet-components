<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION, Tag::MAIN, Tag::DIV])]
#[DefaultTag(Tag::SECTION)]
class Container extends UIComponent {
	use LayoutContainerSize;
	use BackgroundColor;

	/**
	 * @var bool|null $withWrapper
	 * @description Whether to wrap the container element so that the background is full-width
	 */
	protected ?bool $withWrapper = true;

	/**
	 * @var string|null $gradient
	 * @description Name of a gradient to use for the background (requires accompanying CSS to be defined)
	 */
	protected ?string $gradient; // TODO: Not limited by a trait because implementations could have all kinds of gradients they handle themselves

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Container.container');
		$this->set_size_from_attrs($attributes);
		$this->set_background_color_from_attrs($attributes);
		$this->gradient = $attributes['gradient'] ?? null;
		$this->withWrapper = $attributes['withWrapper'] ?? true;
	}

	protected function get_filtered_classes(): array {
		$classes = array_filter(parent::get_filtered_classes(), function ($class) {
			return !in_array($class, ['is-style-wide', 'is-style-fullwidth', 'is-style-narrow']);
		});

		if ($this->size !== ContainerSize::DEFAULT) {
			array_push($classes, "container--{$this->size->value}");
		}

		return array_unique($classes);
	}

	protected function get_outer_classes(): array {
		$classes = ['page-section'];

		if (isset($this->backgroundColor)) {
			array_push($classes, 'bg-' . $this->backgroundColor->value);
		}

		if (isset($this->gradient)) {
			array_push($classes, 'bg-gradient-' . $this->gradient);
		}

		return $classes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'          => $this->tagName->value,
			'withWrapper'  => $this->withWrapper,
			'outerClasses' => $this->get_outer_classes(),
			'classes'      => $this->get_filtered_classes_string(),
			'attributes'   => $this->get_html_attributes(),
			'children'     => $this->innerComponents
		])->render();
	}
}
