<?php
namespace Doubleedesign\Comet\Core;

/**
 * Pullquote component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Feature a quote or excerpt, with an optional citation.
 */
#[AllowedTags([Tag::BLOCKQUOTE])]
#[DefaultTag(Tag::BLOCKQUOTE)]
class Pullquote extends TextElementExtended {
    use ColorTheme;

    /**
     * @var string|null $citation
     * @description Optional citation for the quote
     */
    protected ?string $citation = null;

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Pullquote.pullquote');
        $this->set_color_theme_from_attrs($attributes);
        $this->citation = $attributes['citation'] ?? null;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();
        $classes[] = $this->get_bem_name();

        if (isset($this->backgroundColor)) {
            $classes[] = 'bg-' . $this->backgroundColor->value;
        }

        return $classes;
    }

    public function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            ['data-color-theme' => $this->colorTheme->value]
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            // TODO: Need to handle text colour on the paragraph/citation
            'content'    => $this->content,
            'citation'   => $this->citation,
        ])->render();
    }
}
