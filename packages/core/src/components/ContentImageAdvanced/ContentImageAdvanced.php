<?php
namespace Doubleedesign\Comet\Core;

/**
 * ContentImageAdvanced component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::FIGURE)]
class ContentImageAdvanced extends ContentImageComponent {
    /**
     * TODO: Image property refinements
     * Do we really need focal point *and* offset - how would it be handled if both were set but didn't actually work together?
     * How to handle focal point/offset if there is no aspect ratio set - they wouldn't do anything out of the box
     * That case should probably be treated as a ContentImageBasic instead - how to enforce that?
     */

    /**
     * @var array{x: int, y:int}|null $focalPoint
     * @description The focal point of the image to use when cropping - x and y values between 0 and 100
     */
    protected ?array $focalPoint = null;

    /**
     * @var array{x: int, y:int}|null $offset
     * @description The percentage offsets of the image to use when cropping
     */
    protected ?array $offset = null;

    /**
     * @var ?AspectRatio $aspectRatio
     * @description The aspect ratio to use for the image
     *
     * @default AspectRatio::STANDARD
     */
    protected ?AspectRatio $aspectRatio = AspectRatio::STANDARD;

    public function __construct(array $attributes) {
        $this->focalPoint = $attributes['focalPoint'] ?? null;
        $this->offset = $attributes['offset'] ?? $attributes['imageOffset'] ?? null;
        $this->aspectRatio = $attributes['aspectRatio'] ?? $this->aspectRatio;
        parent::__construct($attributes, 'components.ContentImage.content-image');
        $this->shortName = 'image';
    }

    public function get_local_css_properties(): array {
        $properties = [];

        if ($this->focalPoint) {
            $properties['--focal-point-x'] = $this->focalPoint['x'] . '%';
            $properties['--focal-point-y'] = $this->focalPoint['y'] . '%';
        }
        if ($this->offset) {
            $properties['--offset-x'] = $this->offset['x'] . '%';
            $properties['--offset-y'] = $this->offset['y'] . '%';
        }

        return $properties;
    }

    #[NotImplemented]
    public function render(): void {
        // Check the render method of the parent and see if it needs to be overridden,
        // if not then remove this method and the annotation
    }
}
