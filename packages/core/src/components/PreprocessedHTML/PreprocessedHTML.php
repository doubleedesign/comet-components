<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class PreprocessedHTML {
    protected string $content;

    /**
     * @param  array  $attributes
     * @param  string  $content  - The preprocessed HTML content to render; it is assumed this content has already been sanitised (e.g., using the functions your CMS provides)
     */
    public function __construct(array $attributes, string $content) {
        $this->content = $content;
    }

    public function render(): void {
        echo $this->content;
    }
}
