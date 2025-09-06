<?php
namespace Doubleedesign\Comet\Core;

/**
 * CoverImage component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class CoverImage extends ImageComponent {
    /**
     * @var bool $isParallax
     * @description In relevant contexts, whether the image should be used to achieve a parallax effect (requires CSS to actually execute)
     */
    protected bool $isParallax = false;

    public function __construct(array $attributes) {
        $this->isParallax = $attributes['isParallax'] ?? $this->isParallax;
        parent::__construct($attributes, 'components.CoverImage.cover-image');
    }

    public function get_wrapper_html_attributes(): array {
        return [
            'data-parallax'     => $this->isParallax ? 'true' : 'false'
        ];
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'src'               => $this->src,
            'classes'           => implode(' ', $this->get_filtered_classes()),
            'attributes'        => $this->get_html_attributes(),
            'wrapperAttributes' => $this->get_wrapper_html_attributes(),
        ])->render();
    }
}
