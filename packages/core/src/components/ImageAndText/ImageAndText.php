<?php
namespace Doubleedesign\Comet\Core;

/**
 * ImageAndText component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display some featured text alongside an image.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ImageAndText extends UIComponent {
	/**
	 * @var int $imageMaxWidth
	 * @description The maximum width of the image as a percentage of the available space; may be ignored in small viewports/containers.
	 */
	protected int $imageMaxWidth = 50;

	/**
	 * @var int $contentMaxWidth
	 * @description The maximum width of the content as a percentage of the available space; may be ignored in small viewports/containers.
	 */
	protected int $contentMaxWidth = 50;

	/**
	 * @var Alignment $imageAlign
	 * @description The horizontal alignment of the image within the available space.
	 */
	protected Alignment $imageAlign = Alignment::START;

	/**
	 * @var Alignment $contentAlign
	 * @description The horizontal alignment of the content within the available space.
	 */
	protected Alignment $contentAlign = Alignment::START;

	/**
	 * @var int $overlayAmount
	 * @description The amount in pixels to overlay the content over the image.
	 */
	protected int $overlayAmount = 0;

	/**
	 * @var bool $imageFirst
	 * @description Whether the image should be rendered first in the HTML.
	 */
	protected bool $imageFirst = true;

	/**
	 * @var array<Image|Renderable> $innerComponents
	 * @description An Image component, plus one or more other components to be rendered in the content area.
	 */
	protected array $innerComponents;

	function __construct(array $attributes, array $innerComponents) {
		$this->imageMaxWidth = $attributes['imageMaxWidth'] ?? $this->imageMaxWidth;
		$this->contentMaxWidth = $attributes['contentMaxWidth'] ?? $this->contentMaxWidth;
		$this->imageAlign = isset($attributes['imageAlign']) ? Alignment::fromString($attributes['imageAlign']) : $this->imageAlign;
		$this->contentAlign = isset($attributes['contentAlign']) ? Alignment::fromString($attributes['contentAlign']) : $this->contentAlign;
		$this->overlayAmount = $attributes['overlayAmount'] ?? $this->overlayAmount;
		$this->imageFirst = $attributes['imageFirst'] ?? $this->imageFirst;

		$filteredInnerComponents = array_filter($innerComponents, function($component) {
			return !($component instanceof Image);
		}) ?? [];
		// We are only expecting one top-level Image, so this should be replaced with array_find when we can use PHP 8.4
		$imageComponent = array_filter($innerComponents, function($component) {
			return $component instanceof Image;
		})[0] ?? null;

		$transformedInnerComponents = [
			new Group([
				'context'     => 'image-and-text',
				'shortName'   => 'image',
				'data-halign' => $this->imageAlign->value
			], [
				new Group([
					'context'   => 'image-and-text__image',
					'shortName' => 'inner',
					'style'     => [
						'max-width'  => $this->imageMaxWidth . '%',
						'flex-basis' => $this->imageMaxWidth . '%',
					]
				], [$imageComponent])
			]),
			new Group([
				'context'     => 'image-and-text',
				'shortName'   => 'content',
				'data-halign' => $this->contentAlign->value,
				'style'       => [
					'--overlay-amount' => '-' . $this->overlayAmount . 'px'
				]
			], [
				new Group([
					'context'   => 'image-and-text__content',
					'shortName' => 'inner',
					'style'     => [
						'max-width'  => $this->contentMaxWidth . '%',
						'flex-basis' => $this->contentMaxWidth . '%'
					]
				], $filteredInnerComponents),
			])
		];

		if(!$this->imageFirst) {
			$transformedInnerComponents = array_reverse($transformedInnerComponents);
		}

		parent::__construct($attributes, $transformedInnerComponents, 'components.ImageAndText.image-and-text');
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
