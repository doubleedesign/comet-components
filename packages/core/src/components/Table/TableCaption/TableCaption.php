<?php

namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::CAPTION])]
#[DefaultTag(Tag::CAPTION)]
class TableCaption extends TextElement {
    /**
     * @description Position of the caption relative to the table
     * @supported-values top, bottom
     */
    protected string $position = 'bottom';

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Table.TableCaption.table-caption');
        $this->set_text_align_from_attrs($attributes);
        $this->context = 'table';
        $this->position = $attributes['position'] ?? $this->position;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
        ])->render();
    }
}
