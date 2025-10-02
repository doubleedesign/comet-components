<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class ListItem extends UIComponent {
    protected ?string $content;

    public function __construct(array $attributes, string $content, array $nestedLists = []) {
        $this->content = $content;
        parent::__construct($attributes, $nestedLists, 'components.List.ListItem.list-item');
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            // Workaround for TextElement's sanitisation stripping aria attributes out of rendered link output passed as $content in breadcrumbs,
            // because HTML Purifier simply would not cooperate
            // and we generally expect to have enough control over breadcrumb input data this is probably ok
            'content'    => $this->get_context() ? $this->content : Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
            'children'   => $this->innerComponents
        ])->render();
    }
}
