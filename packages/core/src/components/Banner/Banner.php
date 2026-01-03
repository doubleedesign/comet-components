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
class Banner extends WrappedLayoutComponent {
    use BackgroundColor;
    use ColorTheme;
    use LayoutAlignment;

    /**
     * @var int $contentMaxWidth
     * @description The maximum width of the content area as a percentage of the container
     */
    protected int $contentMaxWidth = 50;

    /**
     * @var string $backgroundType
     * @supported-values 'overlay', 'content'
     */
    protected string $backgroundType = 'content';

    /**
     * @var int $overlayOpacity
     * @description The opacity of the overlay on top of the image (set to 0 to disable the overlay)
     */
    protected int $backgroundOpacity = 0;

    /**
     * @var array $imageProps
     * @description The properties of the background image, as per the fields of the CoverImage component
     */
    protected array $imageProps = [];

    /**
     * @var array<Heading|Paragraph|ButtonGroup|ListComponent|Copy|PreprocessedHTML> $innerComponents
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        $this->backgroundType = $attributes['backgroundType'] ?? $this->backgroundType;
        $this->backgroundOpacity = $attributes['backgroundOpacity'] ?? $this->backgroundOpacity;
        $this->withContainer = false; // do not have the parent component wrap in a container, as we need to handle that separately in this case
        $this->imageProps = $attributes['imageProps'] ?? $this->imageProps;

        $updatedInnerComponents = array(
            new CoverImage([
                ...$this->imageProps,
                'context' => 'banner'
            ]),
            new Container([
                ...Utils::array_pick($attributes, ['size', 'hAlign', 'vAlign']),
                'context'  => 'banner',
                'tagName'  => Tag::DIV->value,
                'isNested' => true
            ],
                array(
                    new Group([
                        'colorTheme' => $attributes['colorTheme'] ?? 'primary',
                        ...($this->backgroundType === 'content' ? ['backgroundColor' => $attributes['backgroundColor'] ?? null] : []),
                        'context'   => 'banner',
                        'shortName' => 'content',
                        'style'     => ['max-width' => $this->contentMaxWidth . '%']
                    ],
                        $innerComponents
                    )
                ))
        );

        if ($this->backgroundType === 'overlay' && $attributes['backgroundColor'] && $attributes['backgroundColor'] !== 'transparent') {
            $attributes['style'] = array(
                '--overlay-opacity' => $this->backgroundOpacity / 100
            );
        }

        // Don't pass attributes to the wrapper(s) that have already been applied to inner components
        unset($attributes['colorTheme']);
        if ($this->backgroundType === 'content') {
            unset($attributes['backgroundColor']);
        }

        parent::__construct($attributes, $updatedInnerComponents, 'components.Banner.banner');
    }
}
