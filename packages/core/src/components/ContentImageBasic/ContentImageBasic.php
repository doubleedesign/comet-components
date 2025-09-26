<?php
namespace Doubleedesign\Comet\Core;

/**
 * ContentImageBasic component
 *
 * @description A basic image within page content
 * @package     Doubleedesign\Comet\Core
 * @version     1.0.0
 */
#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class ContentImageBasic extends ContentImageComponent {
    /**
     * @var string|null $scale
     * @description How to handle how the image fits the available space
     * @supported-values contain, cover
     *
     * This is basically here because the WordPress block editor provides this option along with aspect ratio.
     * The ContentImage class provides more advanced cropping and should NOT also have the scale attribute.
     */
    protected ?string $scale = 'contain';

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
     * @var string|null $href
     * @description URL to link to
     */
    protected ?string $href = null;

    public function __construct(array $attributes) {
        parent::__construct($attributes, 'components.ContentImageBasic.content-image-basic');
        $this->scale = $attributes['scale'] ?? 'contain';
        $this->height = $attributes['height'] ?? null;
        $this->width = $attributes['width'] ?? null;
        $this->href = $attributes['href'] ?? null;
    }

    /**
     * Enables setting behaviour after instantiation, such as by the Gallery component
     *
     * @param  string<'cover'|'contain'>  $behaviour
     *
     * @return void
     */
    public function set_behaviour(string $behaviour): void {
        $this->scale = $behaviour;
    }

    protected function get_inline_styles(): array {
        $styles = [];

        if ($this->height) {
            $styles['height'] = $this->height;
        }

        if ($this->width) {
            $styles['width'] = $this->width;
        }

        return $styles;
    }

    public function get_inner_wrapper_html_attributes(): array {
        return [
            // TODO: Can having both of these set AND width and height cause problems?
            'data-behaviour'    => $this->scale ?? null,
            'data-aspect-ratio' => isset($this->aspectRatio) ? strtolower($this->aspectRatio->name) : null,
        ];
    }

    protected function get_bem_name(): ?string {
        return parent::get_bem_name() . "--basic";
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'src'            => $this->src,
            'href'           => $this->href,
            'caption'        => $this->caption,
            'captionClasses' => ["{$this->get_bem_name()}__caption"],
            'classes'        => implode(' ', $this->get_filtered_classes()),
            'outerAttrs'     => $this->get_outer_wrapper_html_attributes(),
            'innerAttrs'     => $this->get_inner_wrapper_html_attributes(),
            'attributes'     => $this->get_html_attributes(),
        ])->render();
    }

}
