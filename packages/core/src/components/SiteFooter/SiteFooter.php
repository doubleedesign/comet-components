<?php
namespace Doubleedesign\Comet\Core;

class SiteFooter extends UIComponent {
	use HasAllowedTags;

	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;
	/**
	 * @var ?ThemeColor $backgroundColor
	 * @description Background colour keyword
	 */
	protected ?ThemeColor $backgroundColor;
	/**
	 * @var ?Tag $tagName
	 * @description The HTML tag to use for this component
	 */
	protected ?Tag $tagName = Tag::FOOTER;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::FOOTER];
	}

	function __construct(array $attributes, array $innerComponents) {
		$this->tagName = Tag::FOOTER;
		$this->backgroundColor = isset($attributes['backgroundColor']) ? ThemeColor::tryFrom($attributes['backgroundColor']) : ThemeColor::WHITE;
		$this->size = isset($attributes['size']) ? ContainerSize::tryFrom($attributes['size']) : ContainerSize::DEFAULT;

		$this->innerComponents = array(
			new Container(
				// Attributes
				[
					'size' => $this->size->value,
				],
				// Inner components
				array_merge(
					$innerComponents,
					[]
				)
			)
		);

		parent::__construct($attributes, $this->innerComponents, 'components.SiteFooter.site-footer');
	}

	protected function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		return $classes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
