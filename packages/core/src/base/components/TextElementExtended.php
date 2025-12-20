<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([...Settings::BLOCK_PHRASING_ELEMENTS, ...Settings::INLINE_PHRASING_ELEMENTS])]
#[DefaultTag(Tag::SPAN)]
abstract class TextElementExtended extends TextElement {
    use BlockElementModifier;
    use TextColor;

    public function __construct(array $attributes, string $content, string $bladeFile) {
        parent::__construct($attributes, $content, $bladeFile);
        $this->init_bem_structure($bladeFile, $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->set_text_color_from_attrs($attributes);
    }

    public function get_filtered_classes(): array {
        $default = parent::get_filtered_classes();

        return array_filter($default, function($class) {
            // Do not include the text component's name as a class, e.g., we don't want <h2 class="heading">
            return $class !== array_reverse(explode('.', $this->bladeFile))[0];
        });
    }

    /**
     * TODO: Deprecate this. It should usually be decided by the container's colour theme, or custom CSS.
     *
     * @return array|string[]
     */
    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->textColor)) {
            $attributes['data-text-color'] = $this->textColor->value;
        }

        return $attributes;
    }

}
