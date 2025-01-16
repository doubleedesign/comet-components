<?php
namespace Doubleedesign\Comet\Core;

use Exception;

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


	function __construct(array $attributes, string $content) {
		$this->aspectRatio = isset($attributes['aspectRatio']) ? AspectRatio::tryFrom(str_replace('/', ':', $attributes['aspectRatio'])) : null;
		if ($this->aspectRatio) {
			$this->classes[] = 'aspect-ratio-' . str_replace($this->aspectRatio->value, ':', '-');
		}

		if (isset($attributes['scale'])) {
			$this->classes[] = 'image-scale-' . $attributes['scale'];
		}

		if (isset($attributes['linkDestination'])) {
			$this->href = $attributes['linkDestination'];
			$attributes['href'] = $this->href;
			unset($attributes['linkDestination']);
		}

		parent::__construct($attributes, $content, 'components.Image.image');
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

	public function render(): void {
		$blade = BladeService::getInstance();
		$attrs = $this->get_html_attributes();
		$classes = $attrs['className'] ?? '';

		try {
			echo $blade->make($this->bladeFile, [
				'classes'    => $classes,
				'attributes' => array_filter($attrs, fn($k) => $k !== 'class', ARRAY_FILTER_USE_KEY),
			])->render();
		}
		catch (Exception $e) {
			error_log(print_r($e, true));
		}
	}
}
