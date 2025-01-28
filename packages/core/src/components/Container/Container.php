<?php
namespace Doubleedesign\Comet\Core;
use Exception;

class Container extends UIComponent {
	use HasAllowedTags;

	/**
	 * @var array<Renderable> $innerComponents
	 * @description Inner components to be rendered within this component
	 */
	protected array $innerComponents;
	/**
	 * @var ?Tag $tagName
	 * @description The HTML tag to use for this component
	 */
	protected ?Tag $tagName = Tag::SECTION;
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

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::SECTION, Tag::MAIN, Tag::DIV];
	}

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
		$attrs = $this->get_html_attributes();
		$classes = $this->get_filtered_classes_string();

		try {
			echo $blade->make($this->bladeFile, [
				'tag'        => $this->tagName->value,
				'classes'    => $classes,
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
				'children'   => $this->process_inner_components()
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e, true));
		}
	}
}
