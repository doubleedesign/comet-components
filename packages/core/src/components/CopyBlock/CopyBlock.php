<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class CopyBlock extends Container {
    use ColorTheme;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents);
        $this->set_color_theme_from_attrs($attributes);
        if ($this->isNested) {
            $this->withWrapper = false;
        }
        if (!isset($attributes['tagName']) && !$this->isNested) {
            $this->tagName = Tag::SECTION;
        }
    }

    public function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }

    protected function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        if ($this->isNested) {
            $classes = array_filter($classes, function($class) {
                return !in_array($class, ['container', 'copy-block__container']);
            });
        }

        return $classes;
    }

}
