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

    //    public function get_bem_prefix(): string {
    //        return 'responsive-panel__content';
    //    }
}
