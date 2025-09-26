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
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class BannerV2 extends UIComponent {
    use ColorTheme;
    use ImageCropProperties;
    use LayoutAlignment;

    /**
     * @var array{src:string, alt:string} $image URL and alt text for the background image
     */
    protected array $image = [];

    /**
     * @var ContainerSize $size
     * @description The max width of the banner overall
     */
    protected ContainerSize $width;

    /**
     * @var ContainerSize $innerSize
     * @description The max width of the inner content container
     */
    protected ContainerSize $containerWidth;

    /**
     * @var int|null $contentMaxWidth
     * @description The max width of the content inside the inner container, as a percentage
     */
    protected ?int $contentMaxWidth = 100;

    public function __construct(array $attributes, array $innerComponents) {
        $this->image = $this->validate_image_attributes($attributes['image'] ?? []);
        $this->width = ContainerSize::tryFrom($attributes['width']) ?? ContainerSize::FULLWIDTH;
        $this->containerWidth = ContainerSize::tryFrom($attributes['containerWidth']) ?? ContainerSize::DEFAULT;
        $this->contentMaxWidth = isset($attributes['contentMaxWidth']) ? min(max(0, (int)$attributes['contentMaxWidth']), 100) : null;

        $this->set_color_theme_from_attrs($attributes);
        $this->set_focal_point_from_attrs($attributes);
        $this->set_image_offset_from_attrs($attributes);
        $this->set_aspect_ratio_from_attrs($attributes, AspectRatio::CINEMATIC);
        $this->set_layout_alignment_from_attrs($attributes);

        $transformedInnerComponents = array(
            new Container(
                [
                    'tagName'     => 'div',
                    'context'     => 'banner-v2__content',
                    'size'        => $this->containerWidth,
                    'hAlign'      => $this->hAlign,
                    'vAlign'      => $this->vAlign,
                    'withWrapper' => false,
                    'isNested'    => true,
                ],
                [new Group(
                    [
                        'context'    => 'banner-v2',
                        'shortName'  => 'content__inner',
                        'colorTheme' => $this->colorTheme,
                        ...((isset($this->contentMaxWidth) && $this->contentMaxWidth < 100)
                            ? ['style' => ['max-width' => $this->contentMaxWidth . '%']]
                            : []
                        ),
                    ],
                    $innerComponents
                )]
            )
        );

        parent::__construct($attributes, $transformedInnerComponents, 'components.BannerV2.banner-v2');
    }

    private function validate_image_attributes(array $image): array {
        return array(
            'src' => $image['src'] ?? $image['url'] ?? '',
            'alt' => $image['alt'] ?? '',
        );
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->size)) {
            $attributes['data-size'] = $this->size->value;
        }
        if (isset($this->focalPoint) || isset($this->offset)) {
            $attributes['style'] = $this->get_local_css_properties();
        }

        return $attributes;
    }

    protected function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        array_push($classes, 'page-section'); // makes the inner container styling work with *__container syntax

        return $classes;
    }

    protected function get_image_wrapper_attributes(): array {
        $attributes = [];

        if (isset($this->aspectRatio)) {
            $attributes['data-aspect-ratio'] = strtolower($this->aspectRatio->name);
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'               => $this->tagName->value,
            'bemPrefix'         => $this->get_bem_name(),
            'attributes'        => $this->get_html_attributes(),
            'classes'           => $this->get_filtered_classes(),
            'imageWrapperAttrs' => $this->get_image_wrapper_attributes(),
            'imageAttrs'        => $this->image,
            'children'          => $this->innerComponents
        ])->render();
    }
}
