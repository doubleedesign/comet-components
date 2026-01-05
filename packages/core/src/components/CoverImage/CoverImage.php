<?php
namespace Doubleedesign\Comet\Core;

/**
 * CoverImage component
 *
 * Note: This deliberately does not extend other image components because it is not designed to have a caption or certain attributes that they have.
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class CoverImage extends Renderable {
    use BlockElementModifier;
    use ImageCropProperties;

    /**
     * @var string $src
     * @description Image source URL
     */
    protected string $src = '';

    /**
     * @var string $alt
     * @description Alternative text
     */
    protected string $alt = '';

    /**
     * @var bool $isParallax
     * @description In relevant contexts, whether the image should be used to achieve a parallax effect (requires CSS to actually execute)
     */
    protected bool $isParallax = false;

    public function __construct(array $attributes) {
        parent::__construct($attributes, 'components.CoverImage.cover-image');
        $this->init_bem_structure('components.CoverImage.cover-image', $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->src = $attributes['src'] ?? '';
        $this->alt = $attributes['alt'] ?? '';
        $this->isParallax = $attributes['isParallax'] ?? $this->isParallax;
        $this->set_aspect_ratio_from_attrs($attributes, AspectRatio::WIDE);
        $this->set_focal_point_from_attrs($attributes);
    }

    protected function get_html_attributes(): array {
        return array(
            ...parent::get_html_attributes(),
            'data-aspect-ratio' => strtolower($this->aspectRatio->name),
            'data-parallax'     => $this->isParallax ? 'true' : 'false',
            'style'             => $this->get_local_css_properties(['focalPoint']),
        );
    }

    protected function get_image_attributes(): array {
        return array(
            'src' => $this->src,
            'alt' => $this->alt,
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'src'        => $this->src,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'imageAttrs' => $this->get_image_attributes(),
            'bemPrefix'  => $this->get_bem_prefix(),
        ])->render();
    }
}
