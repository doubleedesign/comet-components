<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::LI])]
#[DefaultTag(Tag::LI)]
class ListItemSimple extends TextElementExtended {

    public function __construct(array $attributes, string $content) {
        $bladeFile = 'components.ListComponent.ListItem.list-item';
        parent::__construct($attributes, $content, $bladeFile);
    }

    protected function get_bem_name(): string {
        if ($this->context && str_ends_with($this->context, '__list')) {
            return str_replace('list-item', 'item', parent::get_bem_name());
        }

        return parent::get_bem_name();
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            // Workaround for TextElement's sanitisation stripping aria attributes out of rendered link output passed as $content in breadcrumbs,
            // because HTML Purifier simply would not cooperate
            // and we generally expect to have enough control over breadcrumb input data this is probably ok
            'content'    => $this->context ? $this->content : Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
        ])->render();
    }
}
