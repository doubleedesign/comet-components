<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::HEADER])]
#[DefaultTag(Tag::HEADER)]
class SiteHeader extends LayoutComponent {
	use BackgroundColor;
	use LayoutContainerSize;

	/**
	 * @var ?ContainerSize $size
	 * @description Keyword specifying the relative width of the container for the inner content
	 */
	protected ?ContainerSize $size = ContainerSize::DEFAULT;

	protected ?string $logoUrl = null;

	function __construct(array $attributes, array $innerComponents) {
		$this->logoUrl = $attributes['logoUrl'] ?? null;
		$logo = isset($attributes['logoUrl'])
			? new Image([
				'src'     => $this->logoUrl,
				'alt'     => 'Site logo',
				'href'    => '/',
				'classes' => ['site-header__logo']
			])
			: null;

		$this->innerComponents = array(
			new Container(
			// Attributes
				[
					'size'        => $this->size->value,
					'tagName'     => 'div',
					'withWrapper' => false,
				],
				// Inner components
				array_merge(
					[$logo],
					$innerComponents
				)
			)
		);

		parent::__construct($attributes, $this->innerComponents, 'components.SiteHeader.site-header');
		$this->set_background_color_from_attrs($attributes);
		$this->set_size_from_attrs($attributes);
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}

		return $attributes;
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
