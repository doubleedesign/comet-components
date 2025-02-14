<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::FOOTER])]
#[DefaultTag(Tag::FOOTER)]
class SiteFooter extends UIComponent {

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

	function __construct(array $attributes, array $innerComponents) {
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
