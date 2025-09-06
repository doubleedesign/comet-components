<?php

namespace Doubleedesign\Comet\Core;

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
     * Dev notes: There are fewer options than the layout alignment values, that's why they're not using the Alignment enum
     */
    protected ?string $align = null;

    /**
     * @var string|null $href
     * @description URL to link to
     */
    protected ?string $href = null;

    public function __construct(array $attributes, string $bladeFile) {
        $this->aspectRatio = isset($attributes['aspectRatio'])
            ? ($attributes['aspectRatio'] == '1'
                ? AspectRatio::tryFrom('1:1')
                : AspectRatio::tryFrom(str_replace('/', ':', $attributes['aspectRatio'])))
            : null;
        $this->caption = $attributes['caption'] ?? null;
        $this->align = $attributes['align'] ?? null;
        $this->href = $attributes['href'] ?? null;
        parent::__construct($attributes, $bladeFile);
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
            'class'             => $this->shortName . '__inner',
            'data-aspect-ratio' => isset($this->aspectRatio) ? strtolower($this->aspectRatio->name) : null,
        ];

        return array_filter($attrs, function($value) {
            return $value !== null && $value !== 'false';
        });
    }

}
