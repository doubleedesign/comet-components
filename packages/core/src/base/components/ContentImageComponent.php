<?php

namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::DIV)]
abstract class ContentImageComponent extends ImageComponent {
    /**
     * @var AspectRatio|null $aspectRatio
     * @description Crop image to the given aspect ratio
     */
    protected ?AspectRatio $aspectRatio = null;

    /**
     * @var string|null $caption
     * @description Optional image caption (to appear below the image)
     */
    protected ?string $caption = null;

    /**
     * @var string|null $align
     * @description Image alignment
     * @supported-values left, center, right, full
     *
     * @dev-notes There are fewer options than the layout alignment values, that's why they're not using the Alignment enum
     */
    protected ?string $align = null;

    public function __construct(array $attributes, string $bladeFile) {
        parent::__construct($attributes, $bladeFile);
        $this->aspectRatio = isset($attributes['aspectRatio']) ? AspectRatio::fromString($attributes['aspectRatio']) : null;
        $this->caption = $attributes['caption'] ?? null;
        $this->align = $attributes['align'] ?? null;
    }

    /**
     * Enables setting the aspect ratio after instantiation, such as by the Gallery component
     *
     * @param  AspectRatio|string  $aspectRatio
     *
     * @return void
     */
    public function set_aspect_ratio(AspectRatio|string $aspectRatio): void {
        $this->aspectRatio = $aspectRatio instanceof AspectRatio ? $aspectRatio : AspectRatio::fromString($aspectRatio);
    }

    /**
     * Layout attributes for the element wrapping both the image and the caption (if there is one)
     *
     * @return array
     */
    public function get_outer_wrapper_html_attributes(): array {
        $attrs = [
            'data-align' => $this->align ?? null,
        ];

        return array_filter($attrs, function($value) {
            return $value !== null && $value !== 'false';
        });
    }

    /**
     * Get the classes for the figure element wrapping both the image and the caption (if there is one)
     *
     * @return array|string[]
     */
    protected function get_wrapper_classes(): array {
        if ($this->get_context()) {
            return [$this->get_context() . '__image'];
        }

        return [];
    }

    /**
     * Get the classes for the figcaption element (if there is one)
     *
     * @return array|string[]
     */
    protected function get_caption_classes(): array {
        if ($this->get_context()) {
            return [$this->get_context() . '__image__caption'];
        }

        return [];
    }
}
