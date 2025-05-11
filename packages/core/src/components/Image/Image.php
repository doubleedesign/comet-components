<?php
namespace Doubleedesign\Comet\Core;

/**
 * Image component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render an image with optional caption, link, and display options such as aspect ratio and behaviour within its container.
 */
#[AllowedTags([Tag::FIGURE])]
#[DefaultTag(Tag::FIGURE)]
class Image extends Renderable {
    /**
     * @var string $src
     * @description Image source URL
     */
    protected string $src;

    /**
     * @var array<string> $classes
     * @description CSS classes
     * @supported-values is-style-rounded
     */
    protected ?array $classes = [];

    /**
     * @var string $alt
     * @description Alternative text
     */
    protected string $alt = '';

    /**
     * @var string|null $title
     * @description Optional image title (appears on hover)
     */
    protected ?string $title = null;

    /**
     * @var string|null $caption
     * @description Optional image caption (to appear below the image)
     */
    protected ?string $caption = null;

    /**
     * @var AspectRatio|null $aspectRatio
     * @description Crop image to the given aspect ratio
     * @supported-values 4/3,
     */
    protected ?AspectRatio $aspectRatio = null;

    /**
     * @var string|null $scale
     * @description How to handle how the image fits the available space
     * @supported-values contain, cover
     */
    protected ?string $scale = 'contain';

    /**
     * @var string|null $href
     * @description URL to link to
     */
    protected ?string $href = null;

    /**
     * @var string|null $height
     * @description Set a fixed height for the image
     */
    protected ?string $height = null;

    /**
     * @var string|null $width
     * @description Set a fixed width for the image
     */
    protected ?string $width = null;

    /**
     * @var string|null $align
     * @description Image alignment
     * @supported-values left, center, right, full
     * Dev notes: There are fewer options than the layout alignment values, that's why they're not using the Alignment enum
     */
    protected ?string $align = null;

    /**
     * @var bool $isParallax
     * @description In relevant contexts, whether the image should be used to achieve a parallax effect (requires CSS to actually execute)
     */
    protected bool $isParallax = false;

    public function __construct(array $attributes) {
        $this->src = $attributes['src'] ?? '';
        $this->alt = $attributes['alt'] ?? '';
        $this->title = $attributes['title'] ?? null;
        $this->caption = $attributes['caption'] ?? null;
        $this->href = $attributes['href'] ?? null;
        $this->aspectRatio = isset($attributes['aspectRatio'])
            ? ($attributes['aspectRatio'] == '1'
                ? AspectRatio::tryFrom('1:1')
                : AspectRatio::tryFrom(str_replace('/', ':', $attributes['aspectRatio'])))
            : null;
        $this->scale = $attributes['scale'] ?? 'contain';
        $this->classes = $attributes['classes'] ?? [];
        $this->align = $attributes['align'] ?? null;
        $this->isParallax = $attributes['isParallax'] ?? $this->isParallax;
        $this->height = $attributes['height'] ?? null;
        $this->width = $attributes['width'] ?? null;

        parent::__construct($attributes, 'components.Image.image');
    }

    /**
     * @param  string<'cover'|'contain'>  $behaviour
     *
     * @return void
     */
    public function set_behaviour(string $behaviour): void {
        $this->scale = $behaviour;
    }

    public function get_filtered_classes(): array {
        $classes = array_merge(parent::get_filtered_classes(), [$this->shortName]);

        return array_unique($classes);
    }

    public function get_inline_styles(): array {
        $styles = [];

        if ($this->height) {
            $styles['height'] = $this->height;
        }

        if ($this->width) {
            $styles['width'] = $this->width;
        }

        return $styles;
    }

    public function get_outer_wrapper_html_attributes(): array {
        $attrs = [
            'data-align' => $this->align ?? null,
        ];

        return array_filter($attrs, function($value) {
            return $value !== null && $value !== 'false';
        });
    }

    public function get_inner_wrapper_html_attributes(): array {
        $attrs = [
            'data-aspect-ratio' => isset($this->aspectRatio) ? strtolower($this->aspectRatio->name) : null,
            'data-behaviour'    => $this->scale ?? null,
            'data-parallax'     => $this->isParallax ? 'true' : 'false',
        ];

        return array_filter($attrs, function($value) {
            return $value !== null && $value !== 'false';
        });
    }

    public function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            [
                'alt'   => $this->alt,
                'title' => $this->title
            ]
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'src'            => $this->src,
            'href'           => $this->href,
            'caption'        => $this->caption,
            'captionClasses' => $this->context ? [$this->context . '__image__caption'] : ['image__caption'],
            'classes'        => implode(' ', $this->get_filtered_classes()),
            'outerAttrs'     => $this->get_outer_wrapper_html_attributes(),
            'innerAttrs'     => $this->get_inner_wrapper_html_attributes(),
            'attributes'     => $this->get_html_attributes(),
        ])->render();
    }
}
