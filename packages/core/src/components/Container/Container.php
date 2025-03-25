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
		$this->gradient = $attributes['gradient'] ?? null;
		$this->withWrapper = $attributes['withWrapper'] ?? $this->withWrapper;

		$globalBackground = CometConfig::get_global_background();
		if(isset($attributes['backgroundColor']) && $attributes['backgroundColor'] !== $globalBackground) {
			$this->set_background_color_from_attrs($attributes);
		}
	}

	protected function get_filtered_classes(): array {
		$classes = array_filter(parent::get_filtered_classes(), function($class) {
			// Filter out WordPress + other classes used for the size (size is applied elsewhere)
			return !in_array($class, ['is-style-wide', 'is-style-fullwidth', 'is-style-narrow', 'container--wide', 'container--fullwidth', 'container--narrow']);
		});

		return array_unique($classes);
	}

	protected function get_outer_classes(): array {
		return ['page-section'];
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->size) && $this->size !== ContainerSize::DEFAULT) {
			$attributes['data-size'] = $this->size->value;
		}

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}
		else if(isset($this->gradient)) {
			$attributes['data-background'] = 'gradient-' . $this->gradient;
		}

		return $attributes;
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
