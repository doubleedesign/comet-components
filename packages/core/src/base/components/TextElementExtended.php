<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([...Settings::BLOCK_PHRASING_ELEMENTS, ...Settings::INLINE_PHRASING_ELEMENTS])]
#[DefaultTag(Tag::SPAN)]
abstract class TextElementExtended extends TextElement {
    use BlockElementModifier;

    public function __construct(array $attributes, string $content, string $bladeFile) {
        parent::__construct($attributes, $content, $bladeFile);
        // Only set BEM classnames if context or shortName is provided
        if (isset($attributes['context']) || isset($attributes['shortName'])) {
            $this->init_bem_structure($bladeFile, $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        }
    }

    public function get_filtered_classes(): array {
        $default = parent::get_filtered_classes();

        return array_filter($default, function($class) {
            // Do not include the text component's name as a class, e.g., we don't want <h2 class="heading">
            return $class !== array_reverse(explode('.', $this->bladeFile))[0];
        });
    }
}
