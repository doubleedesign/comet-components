<?php
namespace Doubleedesign\Comet\Core;

/**
 * BannerAdvanced component
 *
 * @package Doubleedesign\Comet\Core
 *
 * @since 0.2.0
 *
 * @description A "hero" banner with an image background and a container for inner content. Supports setting the aspect ratio and crop position (focal point and/or offsets) for the image.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class BannerAdvanced extends LayoutComponent {
    use ImageCropProperties;

    public function __construct(array $attributes, array $innerComponents) {
        $this->set_focal_point_from_attrs($attributes);
        $this->set_image_offset_from_attrs($attributes);
        $this->set_aspect_ratio_from_attrs($attributes, AspectRatio::WIDE);
        parent::__construct($attributes, $innerComponents, 'components.BannerAdvanced.banner-advanced');
    }

    #[NotImplemented]
    public function render(): void {
        // Check the render method of the parent and see if it needs to be overridden,
        // if not then remove this method and the annotation
    }
}
