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
    use ImageCropProperties;

    // Note: To simplify the layout calculations, this component does not support wrapping the image in a link.

    public function __construct(array $attributes) {
        $this->set_focal_point_from_attrs($attributes);
        $this->set_image_offset_from_attrs($attributes);
        $this->set_aspect_ratio_from_attrs($attributes, AspectRatio::STANDARD);
        parent::__construct($attributes, 'components.ContentImageAdvanced.content-image-advanced');
        $this->shortName = 'image';
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
