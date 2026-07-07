<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class PreprocessedHTML extends Renderable {
    use BlockElementModifier {
        BlockElementModifier::get_filtered_classes as protected get_filtered_classes_from_trait;
    }
    protected string $content;

    /**
     * @param  array  $attributes
     * @param  string  $content  - The preprocessed HTML content to render; it is assumed this content has already been sanitised (e.g., using the functions your CMS provides)
     */
    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, '');
        $this->init_bem_structure('components.PreprocessedHTML.preprocessed-html', $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->content = $content;
    }

    public function get_filtered_classes(): array {
        $default = $this->get_filtered_classes_from_trait();

        return array_filter($default, function($class) {
            return !str_ends_with($class, 'preprocessed-html');
        });
    }

    public function render(): void {
        if ((empty($this->get_filtered_classes()) && empty($this->get_html_attributes()))) {
            // If there are no classes or attributes to add, render the content without the wrapper element
            echo $this->content;

            return;
        }

        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'content'    => $this->content
        ])->render();
    }
}
