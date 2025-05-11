<?php
namespace Doubleedesign\Comet\Core;

/**
 * EventList component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::UL, Tag::OL])]
#[DefaultTag(Tag::UL)]
class EventList extends UIComponent {
    /**
     * @var ?string $heading
     * @description The heading for the list.
     */
    protected ?string $heading;

    /**
     * @var array<EventCard> $innerComponents
     * @description Event cards to display.
     */
    protected array $innerComponents;

    /**
     * @var int $maxItemsPerRow
     * @description The maximum number of items to display per row, when the viewport is wide enough to accommodate.
     */
    protected int $maxItemsPerRow = 3;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.EventList.event-list');
        $this->heading = $attributes['heading'] ?? null;
        $this->maxItemsPerRow = $attributes['maxItemsPerRow'] ?? $this->maxItemsPerRow;

        // Automatically add event-list context to the cards if they don't already have their own context set
        // Modify components in place rather than copying because it's more performant
        array_walk($innerComponents, function($card) {
            if ($card instanceof EventCard && !$card->context) {
                $card->set_context('events__list');
            }
        });

        $this->shortName = 'events__list';
    }

    protected function get_html_attributes(): array {
        return array_merge(parent::get_html_attributes(), [
            'data-max-per-row' => $this->maxItemsPerRow,
        ]);
    }

    public function render(): void {
        $blade = BladeService::getInstance();
        $headingComponent = isset($this->heading) ? new Heading(['context' => 'events', 'level' => 2], $this->heading) : null;

        echo $blade->make($this->bladeFile, [
            'heading'    => $headingComponent,
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
