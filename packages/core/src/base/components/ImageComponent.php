<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::FIGURE, Tag::DIV])]
#[DefaultTag(Tag::DIV)]
abstract class ImageComponent extends Renderable {
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

    public function __construct(array $attributes, string $bladeFile) {
        $this->src = $attributes['src'] ?? '';
        $this->alt = $attributes['alt'] ?? '';
        $this->title = $attributes['title'] ?? null;
        $this->classes = $attributes['classes'] ?? [];
        parent::__construct($attributes, $bladeFile);
    }

    public function get_filtered_classes(): array {
        $classes = array_merge(parent::get_filtered_classes(), [$this->shortName]);

        return array_unique($classes);
    }

    public function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            [
                'src'   => $this->src,
                'alt'   => $this->alt,
                'title' => $this->title
            ]
        );
    }
}
