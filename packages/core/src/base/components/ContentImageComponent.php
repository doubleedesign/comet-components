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
}
