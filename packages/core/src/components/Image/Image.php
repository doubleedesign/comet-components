<?php
namespace Doubleedesign\Comet\Core;

class Image extends Renderable {
	/**
	 * @var string $src
	 * @description Image source URL
	 */
	protected string $src;
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 * @supported-values is-style-rounded
	 */
	protected ?array $classes = [];
	/**
	 * @var string $alt
	 * @description Alternative text
	 */
	protected string $alt = '';
	/**
	 * @var string|null $title
	 * @description Optional image title (appears on hover)
	 */
	protected ?string $title = null;
	/**
	 * @var string|null $caption
	 * @description Optional image caption (to appear below the image)
	 */
	protected ?string $caption = null;
	/**
	 * @var AspectRatio|null $aspectRatio
	 * @description Crop image to the given aspect ratio
	 * @supported-values 4/3,
	 */
	protected ?AspectRatio $aspectRatio = null;
	/**
	 * @var string|null $scale
	 * @description How to handle image scaling
	 * @supported-values contain, cover
	 */
	protected ?string $scale = 'contain';
	/**
	 * @var string|null $href
	 * @description URL to link to
	 */
	protected ?string $href = null;
	/**
	 * @var string|null $height
	 * @description Set a fixed height for the image
	 */
	protected ?string $height = null;
	/**
	 * @var string|null $width
	 * @description Set a fixed width for the image
	 */
	protected ?string $width = null;


	function __construct(array $attributes) {
		$this->src = $attributes['src'];
		$this->alt = $attributes['alt'] ?? '';
		$this->title = $attributes['title'] ?? null;
		$this->caption = $attributes['caption'] ?? null;
		$this->href = $attributes['href'] ?? null;
		$this->aspectRatio = isset($attributes['aspectRatio']) ? AspectRatio::tryFrom(str_replace('/', ':', $attributes['aspectRatio'])) : null;
		$this->scale = $attributes['scale'] ?? 'contain';

		parent::__construct($attributes, 'components.Image.image');
	}

	public function get_filtered_classes(): array {
		$classes = [$this->shortName];
		if ($this->scale) {
			$classes[] = 'image--scale-' . $this->scale;
		}
		// TODO: Aspect ratio isn't working
		if ($this->aspectRatio) {
			$classes[] = 'aspect-ratio-' . str_replace($this->aspectRatio->value, ':', '-');
		}

		return array_merge(parent::get_filtered_classes(), $classes);
	}

	public function get_inline_styles(): array {
		$styles = [];

		if ($this->height) {
			$styles['height'] = $this->height;
		}

		if ($this->width) {
			$styles['width'] = $this->width;
		}

		return $styles;
	}

	public function get_html_attributes(): array {
		return array_merge(
			parent::get_html_attributes(),
			[
				'alt'   => $this->alt,
				'title' => $this->title,
			]
		);
	}

	public function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'src'        => $this->src,
			'href'       => $this->href,
			'caption'    => $this->caption,
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
		])->render();
	}
}
