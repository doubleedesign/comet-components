<?php
namespace Doubleedesign\Comet\Core;

/**
 * Gallery component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a grid of images with optional captions, with a range of layout options.
 */
#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::FIGURE)]
class Gallery extends UIComponent {
	/**
	 * @var int $columns
	 * @description The number of columns to use for the layout (may be overridden to fewer in small containers/viewports)
	 * @supported-values 1, 2, 3, 4, 5, 6, 7, 8
	 */
	protected int $columns = 3;
	/**
	 * @var string|null $caption
	 * @description Caption describing the whole gallery; supports inline phrasing HTML tags such as <em> and <strong>
	 */
	protected ?string $caption;
	/**
	 * @var array<Image> $innerComponents
	 * @description The image components to display in the gallery
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		$innerComponentsWithContext = array_map(function(Image $component) use ($attributes) {
			$component->set_context('gallery');
			$component->set_behaviour($attributes['imageCrop'] ? 'cover' : 'contain');
			return $component;
		}, $innerComponents);

		parent::__construct($attributes, $innerComponentsWithContext, 'components.Gallery.gallery');
		$this->columns = $attributes['columns'] ?? $this->columns;
		$this->caption = !empty(trim($attributes['caption'])) ? trim($attributes['caption']) : null;
	}

	function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		$attributes['data-max-columns'] = $this->columns;

		// Provide attributes to be used for layout CSS in smaller containers/viewports
		// to easily allow for things like 6 items = 3 columns, but 4 items = 2 columns until the space is large enough for 4
		if($this->columns > 1) {
			if(count($this->innerComponents) % 2 === 0) {
				$attributes['data-interim-columns'] = 2;
			}
			else if(count($this->innerComponents) % 3 === 0) {
				$attributes['data-interim-columns'] = 3;
			}
		}

		return $attributes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents,
			'caption'    => $this->caption
		])->render();
	}
}
