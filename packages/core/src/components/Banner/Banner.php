<?php
namespace Doubleedesign\Comet\Core;

/**
 * Banner component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description A "hero" banner with an image background and optional overlay, with a container for inner content. Supports parallax effect, minHeight and maxHeight.
 */
#[AllowedTags([Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class Banner extends LayoutComponent {
    use LayoutAlignment;

    /**
     * @var ContainerSize $containerSize
     * @description The size of the container for the content
     */
    protected ContainerSize $containerSize = ContainerSize::DEFAULT;

    /**
     * @var int $contentMaxWidth
     * @description The maximum width of the content area as a percentage of the container (may be overridden to full width for small viewports/containers)
     */
    protected int $contentMaxWidth = 50;

    /**
     * @var string $imageUrl
     * @description The URL of the image to display in the banner
     */
    protected string $imageUrl;

    /**
     * @var string $imageAlt
     * @description The alt text for the image
     */
    protected string $imageAlt;

    /**
     * @var ThemeColor $overlayColor
     * @description The color of the overlay on top of the image
     */
    protected ThemeColor $overlayColor = ThemeColor::DARK;

    /**
     * @var int $overlayOpacity
     * @description The opacity of the overlay on top of the image (set to 0 to disable the overlay)
     */
    protected int $overlayOpacity = 0;

    /**
     * @var bool $isParallax
     * @description Whether the banner should have a fixed background (also known as a parallax effect)
     */
    protected bool $isParallax = false;

    /**
     * @var int $minHeight
     * @description The minimum height of the banner (in px)
     */
    protected int $minHeight = 600;

    /**
     * @var int $maxHeight
     * @description The maximum height of the banner (in vh)
     */
    protected int $maxHeight = 100;

    /**
     * @var array<Heading|Paragraph|ButtonGroup> $innerComponents
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        $this->imageUrl = $attributes['imageUrl'] ?? '';
        $this->imageAlt = $attributes['imageAlt'] ?? '';
        $this->overlayColor = isset($attributes['overlayColor']) ? ThemeColor::tryFrom($attributes['overlayColor']) : $this->overlayColor;
        $this->overlayOpacity = $attributes['overlayOpacity'] ?? $this->overlayOpacity;
        $this->isParallax = $attributes['isParallax'] ?? $this->isParallax;
        $this->minHeight = $attributes['minHeight'] ?? $this->minHeight;
        $this->maxHeight = $attributes['maxHeight'] ?? $this->maxHeight;

        parent::__construct($attributes, $innerComponents, 'components.Banner.banner');
        $this->set_layout_alignment_from_attrs($attributes);
        $this->transform_inner_components();
    }

    private function transform_inner_components(): void {
        $rawInnerComponents = $this->innerComponents;

        $this->innerComponents = [
            new CoverImage(
                [
                    'src'        => $this->imageUrl,
                    'alt'        => $this->imageAlt ?? '',
                    'context'    => 'banner',
                    'isParallax' => $this->isParallax,
                ]
            ),
            new Container(
                array_merge(
                    $this->get_container_attributes(),
                    [
                        'size'        => $this->containerSize->value,
                        'withWrapper' => false,
                        'tagName'     => 'div',
                        'context'     => 'banner',
                        'hAlign'      => $this->hAlign->value,
                        'vAlign'      => $this->vAlign->value,
                    ]
                ),
                [new Group(
                    [
                        'backgroundColor' => $this->backgroundColor ?? null,
                        'context'         => 'banner__container',
                        'shortName'       => 'inner',
                        'style'           => [
                            'max-width' => $this->contentMaxWidth . '%'
                        ]
                    ],
                    $rawInnerComponents)
                ]
            ),
            new Group(
                [
                    'context'         => 'banner',
                    'shortName'       => 'overlay',
                    'backgroundColor' => $this->overlayColor->value ?? null,
                    'style'           => [
                        'opacity' => $this->overlayOpacity / 100
                    ]
                ],
                []
            )
        ];
    }

    private function get_container_attributes(): array {
        $attrs = $this->get_html_attributes();

        return array_filter($attrs, function($key) {
            return $key !== 'data-background' && $key !== 'style';
        }, ARRAY_FILTER_USE_KEY);
    }

    public function get_inline_styles_string(): string {
        $styles['min-height'] = $this->minHeight . 'px';
        $styles['max-height'] = $this->maxHeight . 'dvh';

        $result = '';
        foreach ($styles as $key => $value) {
            $result .= $key . ':' . $value . ';';
        }

        return $result;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'attributes' => ['style' => $this->get_inline_styles_string()],
            'classes'    => $this->get_filtered_classes_string(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
