<?php

namespace Doubleedesign\Comet\Core;

/**
 * Image component
 *
 * @version 1.0.0
 * @description Render an image with optional caption, link, and display options such as aspect ratio and behaviour within its container.
 */
#[AllowedTags([Tag::FIGURE])]
#[DefaultTag(Tag::FIGURE)]
class Image extends Renderable {
    /**
     * @description Image source URL
     */
    protected string $src;

    /**
     * @var array<string>
     * @description CSS classes
     * @supported-values is-style-rounded
     */
    protected ?array $classes = [];

    /**
     * @description Alternative text
     */
    protected string $alt = '';

    /**
     * @description Optional image title (appears on hover)
     */
    protected ?string $title = null;

    /**
     * @description Optional image caption (to appear below the image)
     */
    protected ?string $caption = null;

    /**
     * @description Crop image to the given aspect ratio
     * @supported-values 4/3,
     */
    protected ?AspectRatio $aspectRatio = null;

    /**
     * @description How to handle how the image fits the available space
     * @supported-values contain, cover
     */
    protected ?string $scale = 'contain';

    /**
     * @description URL to link to
     */
    protected ?string $href = null;

    /**
     * @description Set a fixed height for the image
     */
    protected ?string $height = null;

    /**
     * @description Set a fixed width for the image
     */
    protected ?string $width = null;

    /**
     * @description Image alignment
     * @supported-values left, center, right, full
     * Dev notes: There are fewer options than the layout alignment values, that's why they're not using the Alignment enum
     */
    protected ?string $align = null;

    /**
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
                'title' => $this->title,
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
