<?php
namespace Doubleedesign\Comet\Core;

/**
 * TODO: Image property refinements/documentation
 * Certain combinations of properties are designed to work together whereas others will/should be ignored depending on the scenario.
 * e.g., aspect ratio + offset for cropped images;
 *       focal point with object-fit:cover for when aspect ratio is not used / is ignored (e.g., banners in small containers/viewports)
 */
trait ImageCropProperties {
    /**
     * @var AspectRatio|null $aspectRatio
     * @description Crop image to the given aspect ratio by default
     */
    protected ?AspectRatio $aspectRatio = null;

    /**
     * @var ?Orientation $originalImageOrientation
     * @description The original orientation of the image; can be used as a data attribute to tweak CSS for cropping
     */
    protected ?Orientation $originalImageOrientation = null;

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
        $this->aspectRatio = AspectRatio::tryFrom($attributes['aspectRatio'] ?? $attributes['aspect_ratio'] ?? '') ?? $default;
    }

    protected function set_original_image_orientation_from_attrs(array $attributes): void {
        $orientation = $attributes['originalImageOrientation'] ?? $attributes['original_image_orientation'] ?? null;
        if ($orientation) {
            $this->originalImageOrientation = Orientation::tryFrom($orientation);
        }
    }

    protected function set_focal_point_from_attrs(array $attributes): void {
        $field = $attributes['focalPoint'] ?? $attributes['focal_point'] ?? null;
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
        $field = $attributes['offset'] ?? $attributes['imageOffset'] ?? $attributes['image_offset'] ?? null;
        if (!$field) {
            return;
        }

        // For now we trust that the editing context will provide reasonable values.
        $this->offset = [
            'x' => (int)$field['x'],
            'y' => (int)$field['y'],
        ];
    }

    /**
     * Build a string to be used in the style attribute
     * for local CSS properties that can be used to control image cropping
     *
     * @param  array  $include  List of properties to include in the output; default is all
     *
     * @return string
     */
    public function get_local_css_properties(array $include = ['focalPoint', 'offset']): string {
        $properties = [];

        if ($this->focalPoint && in_array('focalPoint', $include)) {
            $properties['--focal-point-x'] = $this->focalPoint['x'] . '%';
            $properties['--focal-point-y'] = $this->focalPoint['y'] . '%';
        }
        if ($this->offset && in_array('offset', $include)) {
            $properties['--offset-x'] = $this->offset['x'] . '%';
            $properties['--offset-y'] = $this->offset['y'] . '%';
        }

        // Return as a string to use in the style attribute
        return implode(';', array_map(
            fn($key, $value) => "$key:$value",
            array_keys($properties),
            $properties
        ));
    }

}
