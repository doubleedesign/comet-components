<?php
namespace Doubleedesign\Comet\Core;

// Note: This component's tag changes responsively,
// determined by the Vue component that renders within ResponsivePanels
#[AllowedTags([Tag::DIV, Tag::DETAILS])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanel extends PanelComponent {

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.ResponsivePanels.ResponsivePanel.responsive-panel');
    }

    public function get_bem_prefix(): string {
        $default = parent::get_bem_prefix();

        // Get around some issues where we get "responsivepanels" as the context where we don't want it
        $result = str_replace('responsivepanels__', '', $default);
        if (str_starts_with($result, '__')) {
            return str_replace('__', '', $result);
        }

        return $result;
    }

    public function get_filtered_classes(): array {
        $transformed = array_map(function($class) {
            return str_replace('responsivepanels', '', $class);
        }, parent::get_filtered_classes());

        return array_map(function($class) {
            // Get around some issues where we get "responsivepanels" as the context where we don't want it
            if (str_starts_with($class, '__')) {
                $class = str_replace('__', '', $class);
            }

            if ($class === $this->get_bem_prefix()) {
                return "{$class}__content";
            }

            return $class;
        }, $transformed);
    }
}
