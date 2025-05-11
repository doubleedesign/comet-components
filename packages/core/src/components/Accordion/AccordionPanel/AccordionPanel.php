<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DETAILS])]
#[DefaultTag(Tag::DETAILS)]
class AccordionPanel extends PanelComponent {

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Accordion.AccordionPanel.accordion-panel');
        $this->context = 'accordion__panel';
    }

    protected function get_bem_name(): ?string {
        return 'accordion__panel__content';
    }
}
