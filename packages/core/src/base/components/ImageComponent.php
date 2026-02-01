<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::DIV)]
abstract class ImageComponent extends Renderable {
    use BlockElementModifier;

    /**
     * @var string $src
     * @description Image source URL
     */
    protected string $src;

    /**
     * @var string|null $style
     * @description Visual style variation.
     *
     * @supported-values: 'rounded', 'polaroid'
     */
    protected ?string $styleName = null;

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

    public function __construct(array $attributes, string $bladeFile) {
        $this->src = $attributes['src'] ?? '';
        $this->alt = $attributes['alt'] ?? '';
        $this->title = $attributes['title'] ?? null;
        $this->classes = $attributes['classes'] ?? [];
        $this->styleName = $attributes['styleName'] ?? null;
        parent::__construct($attributes, $bladeFile);
        $this->init_bem_structure($bladeFile, $attributes['context'] ?? null, $attributes['shortName'] ?? 'image');
    }

    protected function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            [
                'src'   => $this->src,
                'alt'   => $this->alt,
                'title' => $this->title,
            ]
        );
    }
}
