<?php
namespace Doubleedesign\Comet\Core;

/**
 * TODO: Image property refinements
 * Do we really need focal point *and* offset - how would it be handled if both were set but didn't actually work together?
 * How to handle focal point/offset if there is no aspect ratio set - they wouldn't do anything out of the box
 * That case should probably be treated as a ContentImageBasic instead - how to enforce that?
 */
trait ImageCropProperties {
    /**
     * @var AspectRatio|null $aspectRatio
     * @description Crop banner image to the given aspect ratio
     */
    protected ?AspectRatio $aspectRatio = null;

    /**
     * @var array{x: int, y:int}|null $focalPoint
     * @description The focal point of the image to use when cropping - x and y values between 0 and 100
     */
    protected ?array $focalPoint = ['x' => 50, 'y' => 50];

    /**
     * @var array{x: int, y:int}|null $offset
     * @description The percentage offsets of the image to use when cropping
     */
    protected ?array $offset = ['x' => 0, 'y' => 0];

    protected function set_aspect_ratio_from_attrs(array $attributes, AspectRatio $default): void {
        $this->aspectRatio = AspectRatio::tryFrom($attributes['aspectRatio'] ?? '') ?? $default;
    }

    protected function set_focal_point_from_attrs(array $attributes): void {
        $field = $attributes['focalPoint'] ?? null;
        if (!$field) {
            return;
        }

        if (is_array($field) && isset($field['x']) && isset($field['y'])) {
            $this->focalPoint = [
                'x' => max(0, min(100, (int)$field['x'])),
                'y' => max(0, min(100, (int)$field['y'])),
            ];
        }
    }

    protected function set_image_offset_from_attrs(array $attributes): void {
        $field = $attributes['offset'] ?? $attributes['imageOffset'] ?? null;
        if (!$field) {
            return;
        }

        if (isset($field['x']) && isset($field['y']) && is_array($field)) {
            $this->offset = [
                'x' => max(0, min(100, (int)$field['x'])),
                'y' => max(0, min(100, (int)$field['y'])),
            ];
        }
    }

    /**
     * Build a string to be used in the style attribute
     * for local CSS properties that can be used to control image cropping
     *
     * @return string
     */
    public function get_local_css_properties(): string {
        $properties = [];

        if ($this->focalPoint) {
            $properties['--focal-point-x'] = $this->focalPoint['x'] . '%';
            $properties['--focal-point-y'] = $this->focalPoint['y'] . '%';
        }
        if ($this->offset) {
            $properties['--offset-x'] = $this->offset['x'] . '%';
            $properties['--offset-y'] = $this->offset['y'] . '%';
        }

        // Return as a string to use in the style attribute
        return implode(';', array_map(
            fn($key, $value) => "$key:$value;",
            array_keys($properties),
            $properties
        ));
    }

}
