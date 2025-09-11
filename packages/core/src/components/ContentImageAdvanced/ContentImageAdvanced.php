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

    // Note: To simplify the layout calculations, this component does not support wrapping the image in a link.

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

    public function __construct(array $attributes) {
        $this->focalPoint = $attributes['focalPoint'] ?? null;
        $this->set_image_offset_from_attrs($attributes);
        $this->aspectRatio = AspectRatio::tryFrom($attributes['aspectRatio']) ?? $this->aspectRatio;
        parent::__construct($attributes, 'components.ContentImageAdvanced.content-image-advanced');
        $this->shortName = 'image';
    }

    private function set_image_offset_from_attrs(array $attributes): void {
        $field = $attributes['offset'] ?? $attributes['imageOffset'];
        if (!$field) {
            $this->offset = ['x' => 0, 'y' => 0];
        }

        if (is_array($field) && isset($field['x']) && isset($field['y'])) {
            $this->offset = [
                'x' => max(0, min(100, (int)$field['x'])),
                'y' => max(0, min(100, (int)$field['y'])),
            ];
        }
        else {
            $this->offset = ['x' => 0, 'y' => 0];
        }
    }

    public function get_local_css_properties() {
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

    protected function get_bem_name(): string {
        return parent::get_bem_name() . "--advanced";
    }

    public function get_outer_wrapper_html_attributes(): array {
        return [
            parent::get_outer_wrapper_html_attributes(),
            'style' => $this->get_local_css_properties()
        ];
    }

    public function get_filtered_classes(): array {
        return array_unique([
            $this->shortName,
            $this->get_bem_name(),
            ...parent::get_filtered_classes()
        ]);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'     => implode(' ', $this->get_filtered_classes()),
            'outerAttrs'  => $this->get_outer_wrapper_html_attributes(),
            'attributes'  => $this->get_html_attributes(), // Attributes for the image itself
            'src'         => $this->src, // Blade template in IDE is happier if we specify src explicitly
            // because we were getting to too many layers of attribute/class methods when we can just do stuff in the template
            'bemPrefix'   => $this->get_bem_name(),
            'aspectRatio' => isset($this->aspectRatio) ? strtolower($this->aspectRatio->name) : null,
        ])->render();
    }
}
