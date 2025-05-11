<?php
namespace Doubleedesign\Comet\Core;

/**
 * DateBlockEvent component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a summary of an event including title, date, location and links.
 */
#[AllowedTags([Tag::DIV, Tag::LI])]
#[DefaultTag(Tag::DIV)]
class EventCard extends UIComponent {
    /**
     * @var ?DateBlock|DateRangeBlock $dateComponent
     * @description The component displaying the date.
     */
    protected DateBlock|DateRangeBlock|null $dateComponent;

    /**
     * @var string $title
     * @description The name of the event.
     */
    protected string $name;

    /**
     * @var string|null $detailUrl
     * @description The URL to be used for the detail page.
     */
    protected ?string $detailUrl;

    /**
     * @var array|null $externalLink
     * @description An associative array with fields URL, label, and target for an external link to a separate ticketing, registration, or other third-party site.
     */
    protected ?array $externalLink = [];

    /**
     * @var string|null $location
     * @description The location of the event, such as the name of the venue or an address.
     */
    protected ?string $location;

    public function __construct(array $attributes) {
        $this->dateComponent = $attributes['dateComponent'] ?? null;
        $this->name = $attributes['name'] ?? 'Untitled Event';
        $this->detailUrl = $attributes['detailUrl'] ?? null;
        $this->externalLink = $attributes['externalLink'] ?? null;
        $this->location = $attributes['location'] ?? null;

        $transformedInnerComponents = [];
        $links = [];
        if ($this->location) {
            array_push($transformedInnerComponents, new Group(['shortName' => 'location'], [
                new IconWithText(['icon' => 'fa-map-location-dot', 'colorTheme' => 'secondary'], [new Paragraph([], $this->location)])
            ]));
        }
        if ($this->detailUrl) {
            array_push($links, new Link([
                'href'       => $this->detailUrl,
                'iconPrefix' => 'fa-light',
                'icon'       => 'fa-arrow-right'
            ], 'Read more')
            );
        }
        if ($this->externalLink) {
            array_push($links, new Link([
                'href'       => $this->externalLink['url'],
                'target'     => $this->externalLink['target'] ?? null,
                'iconPrefix' => 'fa-regular',
                'icon'       => 'fa-arrow-up-right-from-square'
            ], $this->externalLink['title'] ?? 'Read more')
            );
        }
        if (!empty($links)) {
            array_push($transformedInnerComponents, new Group(['shortName' => 'links'], $links));
        }

        $newInnerComponents = $this->dateComponent ? [
            $this->dateComponent,
            new Group(['shortName' => 'content'], [
                new Heading(['level' => 3], $this->name),
                new Group(['shortName' => 'content__inner'], $transformedInnerComponents),
            ])
        ] : [];

        parent::__construct($attributes, $newInnerComponents, 'components.EventCard.event-card');

    }

    private function apply_context_to_inner_components($components, $append = ''): void {
        array_walk($components, function($component) use ($append) {
            if ($component instanceof Group || $component instanceof Heading) {
                $component->set_context("{$this->context}__{$this->shortName}{$append}");
            }
            if ($component instanceof Group && $component->innerComponents) {
                $this->apply_context_to_inner_components($component->innerComponents, "__{$component->shortName}");
            }
        });
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        // The context isn't available in the constructor if it was updated by the parent EventList, so we need to make some adjustments here
        if ($this->context === 'events__list') {
            $this->tagName = Tag::LI;
            $this->shortName = 'card';
            $this->apply_context_to_inner_components($this->innerComponents);
        }

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes_string(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
