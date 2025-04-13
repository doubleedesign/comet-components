<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class Banner extends LayoutComponent {
	/**
	 * @var ContainerSize $containerSize
	 * @description The size of the container for the content
	 */
	protected ContainerSize $containerSize = ContainerSize::DEFAULT;
	/**
	 * @var int $contentMaxWidth
	 * @description The maximum width of the content area as a percentage of the container (may be overridden to full width for small viewports/containers)
	 */
	protected int $contentMaxWidth = 50;
	/**
	 * @var string $imageUrl
	 * @description The URL of the image to display in the banner
	 */
	protected string $imageUrl;
	/**
	 * @var string $imageAlt
	 * @description The alt text for the image
	 */
	protected string $imageAlt;
	/**
	 * @var ThemeColor $overlayColor
	 * @description The color of the overlay on top of the image
	 */
	protected ThemeColor $overlayColor = ThemeColor::DARK;
	/**
	 * @var int $overlayOpacity
	 * @description The opacity of the overlay on top of the image (set to 0 to disable the overlay)
	 */
	protected int $overlayOpacity = 0;
	/**
	 * @var bool $isParallax
	 * @description Whether the banner should have a fixed background (also known as a parallax effect)
	 */
	protected bool $isParallax = false;
	/**
	 * @var int $minHeight
	 * @description The minimum height of the banner (in px)
	 */
	protected int $minHeight = 600;
	/**
	 * @var int $maxHeight
	 * @description The maximum height of the banner (in vh)
	 */
	protected int $maxHeight = 100;
	/**
	 * @var array<int, int> $focalPoint
	 * @description The X and Y coordinates of the focal point of the image
	 */
	protected array $focalPoint = [50, 50];
	/**
	 * @var array<Heading|Paragraph|ButtonGroup> $innerComponents
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		$this->imageUrl = $attributes['imageUrl'] ?? '';
		$this->imageAlt = $attributes['imageAlt'] ?? '';
		$this->overlayColor = $attributes['overlayColor'] ? ThemeColor::tryFrom($attributes['overlayColor']) : $this->overlayColor;
		$this->overlayOpacity = $attributes['overlayOpacity'] ?? $this->overlayOpacity;
		$this->isParallax = $attributes['isParallax'] ?? $this->isParallax;
		$this->minHeight = $attributes['minHeight'] ?? $this->minHeight;
		$this->maxHeight = $attributes['maxHeight'] ?? $this->maxHeight;

		// WordPress provides focal point as 0-1, but we want 0-100
		if($attributes['focalPoint']) {
			$focalPointX = $attributes['focalPoint']['x'] < 1 ? $attributes['focalPoint']['x'] * 100 : $attributes['focalPoint']['x'];
			$focalPointY = $attributes['focalPoint']['y'] < 1 ? $attributes['focalPoint']['y'] * 100 : $attributes['focalPoint']['y'];
			$this->focalPoint = [intval($focalPointX), intval($focalPointY)];
		}

		parent::__construct($attributes, $innerComponents, 'components.Banner.banner');
		$this->transform_inner_components();
	}

	private function transform_inner_components(): void {
		$rawInnerComponents = $this->innerComponents;

		$this->innerComponents = [
			new Image(
				[
					'src'        => $this->imageUrl,
					'alt'        => $this->imageAlt ?? '',
					'scale'      => 'cover',
					'context'    => 'banner',
					'isParallax' => $this->isParallax,
					'style'      => $this->get_image_block_inline_styles()
				]
			),
			new Container(
				array_merge(
					$this->get_container_attributes(),
					[
						'size'        => $this->containerSize->value,
						'withWrapper' => false,
						'tagName'     => 'div',
						'context'     => 'banner',
					]
				),
				[new Group(
					[
						'backgroundColor' => $this->backgroundColor ?? null,
						'context'         => 'banner__container',
						'shortName'       => 'inner',
						'style'           => [
							'max-width' => $this->contentMaxWidth . '%'
						]
					],
					$rawInnerComponents)
				]
			),
			new Group(
				[
					'context'         => 'banner',
					'shortName'       => 'overlay',
					'backgroundColor' => $this->overlayColor->value ?? null,
					'style'           => [
						'opacity' => $this->overlayOpacity / 100
					]
				],
				[]
			)
		];
	}

	private function get_container_attributes(): array {
		$attrs = $this->get_html_attributes();

		return array_filter($attrs, function($key) {
			return $key !== 'data-background';
		}, ARRAY_FILTER_USE_KEY);
	}

	private function get_image_block_inline_styles(): array {
		$styles = [];

		if(!$this->isParallax && $this->focalPoint) {
			$styles['object-position'] = $this->focalPoint[0] . '% ' . $this->focalPoint[1] . '%';
		}

		return $styles;
	}
	
	function get_inline_styles(): array {
		$styles['min-height'] = $this->minHeight . 'px';
		$styles['max-height'] = $this->maxHeight . 'vh';

		return $styles;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			// No attributes here because they get passed down to the inner Container
			'classes'  => $this->get_filtered_classes_string(),
			'children' => $this->innerComponents
		])->render();
	}
}
