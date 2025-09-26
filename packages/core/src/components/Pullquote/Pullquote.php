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
    use BackgroundColor;
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
        $this->set_color_theme_from_attrs($attributes);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->backgroundColor) {
            $attributes['data-background-color'] = $this->backgroundColor->value;
        }

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            // TODO: Need to handle text colour on the paragraph/citation
            'content'    => $this->content,
            'citation'   => $this->citation,
        ])->render();
    }
}
