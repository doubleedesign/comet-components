<?php
namespace Doubleedesign\Comet\Core;

/**
 * ContentImageAdvanced component
 *
 * @description An image within content that supports advanced cropping options (aspect ratio, focal point and/or offsets).
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::FIGURE)]
class ContentImageAdvanced extends ContentImageComponent {
    use ImageCropProperties;

    // Note: To simplify the layout calculations, this component does not support wrapping the image in a link.
    public function __construct(array $attributes) {
        $this->set_bem_modifier('advanced');
        parent::__construct($attributes, 'components.ContentImageAdvanced.content-image-advanced');
        $this->set_focal_point_from_attrs($attributes);
        $this->set_image_offset_from_attrs($attributes);
        $this->set_aspect_ratio_from_attrs($attributes, AspectRatio::STANDARD);
        $this->set_original_image_orientation_from_attrs($attributes);
    }

    public function get_outer_wrapper_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($attributes['style'])) {
            return [
                ...$attributes,
                'style' => $this->get_local_css_properties() . '; ' . $attributes['style']
            ];
        }

        return [
            ...$attributes,
            'style'      => $this->get_local_css_properties(),
        ];
    }

    public function get_inner_wrapper_html_attributes(): array {
        $attrs = [
            'data-aspect-ratio' => strtolower($this->aspectRatio->name) ?? null,
            'data-style'        => $this->styleName ?? null,
        ];

        if (isset($this->originalImageOrientation)) {
            $attrs['data-original-orientation'] = $this->originalImageOrientation->value;
        }

        return $attrs;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        unset($attributes['style']); // Remove style attribute as it's handled in the wrapper

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'                      => $this->tagName->value,
            'classes'                  => $this->get_filtered_classes(),
            'outerAttrs'               => $this->get_outer_wrapper_html_attributes(),
            'innerAttrs'               => $this->get_inner_wrapper_html_attributes(),
            'attributes'               => $this->get_html_attributes(), // Attributes for the image itself
            'bemPrefix'                => $this->get_bem_structure()['modifier'] ? "{$this->get_bem_prefix()}--{$this->get_bem_structure()['modifier']}" : $this->get_bem_prefix(),
            'caption'                  => $this->caption ?? null
        ])->render();
    }
}
