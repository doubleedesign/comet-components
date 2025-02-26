<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::FOOTER])]
#[DefaultTag(Tag::FOOTER)]
class SiteFooter extends UIComponent {
	use BackgroundColor;
	use LayoutContainerSize;

	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;

	function __construct(array $attributes, array $innerComponents) {
		$this->set_background_color_from_attrs($attributes);
		$this->set_size_from_attrs($attributes);

		$this->innerComponents = array(
			new Container(
				// Attributes
				[
					'size' => $this->size->value,
					'withWrapper' => false
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
