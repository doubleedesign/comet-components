<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class Banner extends UIComponent {

	/**
	 * @var ThemeColor|null $backgroundColor
	 * @description The background colour of the content area
	 */
	protected ?ThemeColor $backgroundColor;
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

		$transformedInnerComponents = [
			new Group(
				[
					'context'       => 'banner',
					'shortName'     => 'image',
					'data-parallax' => $this->isParallax ? 'true' : 'false'
				],
				[
					new Image(
						[
							'src'   => $this->imageUrl,
							'alt'   => $this->imageAlt ?? '',
							'scale' => 'cover',
							'style' => $this->get_image_block_inline_styles()
						]
					)
				]
			),
			new Group(
				[
					'context'           => 'banner',
					'shortName'         => 'content',
					'justifyContent'    => $attributes['justifyContent'] ?? $attributes['layout']['justifyContent'] ?? null,
					'verticalAlignment' => $attributes['verticalAlignment'] ?? null
				],
				[new Container(
					[
						'size'        => $this->containerSize->value,
						'withWrapper' => false,
						'tagName'     => 'div'
					],
					[new Group(
						[
							'backgroundColor' => $attributes['backgroundColor'] ?? null,
							'context'         => 'banner__content',
							'shortName'       => 'inner',
							'style'           => [
								'max-width' => $this->contentMaxWidth . '%'
							]
						],
						$innerComponents)
					]
				)]
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

		parent::__construct($attributes, $transformedInnerComponents, 'components.Banner.banner');
	}

	function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		// Filter out the bg-colour class because it's the only thing in LayoutComponent we don't want,
		// so rather than repeat all the alignment code I'll just hack this
		return array_filter($classes, function($class) {
			return !str_starts_with($class, 'bg-');
		});
	}

	function get_inline_styles(): array {
		$styles['min-height'] = $this->minHeight . 'px';
		$styles['max-height'] = $this->maxHeight . 'vh';

		return $styles;
	}

	function get_image_block_inline_styles(): array {
		$styles = [];

		if(!$this->isParallax && $this->focalPoint) {
			$styles['object-position'] = $this->focalPoint[0] . '% ' . $this->focalPoint[1] . '%';
		}

		return $styles;
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
