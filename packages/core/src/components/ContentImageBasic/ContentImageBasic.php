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
    use LayoutOrientation;

    /**
     * @var string|null $scale
     * @description How to handle how the image fits the available space
     * @supported-values contain, cover
     *
     * Note: The ContentImageAdvanced class provides more advanced cropping and should NOT also have the scale attribute, that's why this is here.
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
        $this->set_bem_modifier('basic');
        $this->set_orientation($attributes);
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

    public function get_outer_wrapper_html_attributes(): array {
        $attributes = parent::get_outer_wrapper_html_attributes();

        if (isset($this->orientation) && isset($this->caption)) {
            $attributes['data-orientation'] = $this->orientation->value;
        }

        return $attributes;
    }

    public function get_inner_wrapper_html_attributes(): array {
        return [
            'data-style'        => $this->styleName ?? null,
            // TODO: Can having both of these set AND width and height cause problems?
            'data-behaviour'    => $this->scale ?? null,
            'data-aspect-ratio' => isset($this->aspectRatio) ? strtolower($this->aspectRatio->name) : null,
        ];
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Keep basic "image" classes if there is also context passed down / custom shortname
        if ($this->get_context()) {
            array_push($classes, 'image');
            $modifier = $this->get_bem_structure()['modifier'];
            if ($modifier) {
                array_push($classes, "image--{$modifier}");
            }
        }

        return array_unique($classes);
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'src'            => $this->src,
            'href'           => $this->href,
            'caption'        => $this->caption,
            'wrapperClasses' => $this->get_wrapper_classes(),
            'captionClasses' => $this->get_caption_classes(),
            'classes'        => $this->get_filtered_classes(),
            'outerAttrs'     => $this->get_outer_wrapper_html_attributes(),
            'innerAttrs'     => $this->get_inner_wrapper_html_attributes(),
            'attributes'     => $this->get_html_attributes(),
        ])->render();
    }

}
