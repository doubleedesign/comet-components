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
#[DefaultTag(Tag::DIV)]
class CallToAction extends LayoutComponent {
    use ColorTheme;
    use LayoutContainerSize;

    /**
     * @var array<Heading|Paragraph|ListComponent|ButtonGroup> $innerComponents
     */
    protected array $innerComponents;

    /**
     * @var bool $isNested
     * @description Whether this CallToAction is nested inside another LayoutComponent
     * @default-value true
     */
    protected bool $isNested = true;

    /**
     * @param  array  $attributes
     * @param  array<Heading|Paragraph|ButtonGroup>  $innerComponents
     */
    public function __construct(array $attributes, array $innerComponents) {
        $this->isNested = isset($attributes['isNested']) ? filter_var($attributes['isNested'], FILTER_VALIDATE_BOOLEAN) : $this->isNested;
        if (!$this->isNested) {
            $this->set_size_from_attrs($attributes);
            $this->add_container($innerComponents);
        }

        parent::__construct($attributes, $innerComponents, 'components.CallToAction.call-to-action');
        $this->set_color_theme_from_attrs($attributes);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
