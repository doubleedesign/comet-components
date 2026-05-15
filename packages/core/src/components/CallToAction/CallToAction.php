<?php
namespace Doubleedesign\Comet\Core;

/**
 * Call-To-Action component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Highlight important information and prompt the user to take action.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ASIDE])]
#[DefaultTag(Tag::SECTION)]
class CallToAction extends LayoutComponent {
    use ColorPair;

    /**
     * @param  array  $attributes
     * @param  array<Heading|Paragraph|ButtonGroup|PreprocessedHTML>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        $this->set_color_pair($attributes);
        parent::__construct($attributes, $innerComponents, 'components.CallToAction.call-to-action');

    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->get_background_color() !== null) {
            $attributes['data-background'] = $this->get_background_color()->value;
        }

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }
}
