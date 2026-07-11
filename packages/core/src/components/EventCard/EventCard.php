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
     * @var array{url:string, label:string, target:string}|null $externalLink
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
        $this->externalLink = is_array($attributes['externalLink']) ? $attributes['externalLink'] : null;
        $this->location = $attributes['location'] ?? null;
        parent::__construct($attributes, [], 'components.EventCard.event-card'); // call before transforming inner components so BEM context is available

        $transformedInnerComponents = [];
        $links = [];
        if ($this->location) {
            array_push($transformedInnerComponents,
                new IconWithText([
                    // TODO: Make icon and colorTheme configurable
                    'icon'       => 'fa-map-location-dot',
                    'colorTheme' => 'secondary',
                    'aria-label' => 'Location'
                ], [new Paragraph([], $this->location)])
            );
        }
        if ($this->detailUrl) {
            array_push($links, new Link([
                'href'       => $this->detailUrl,
                'iconPrefix' => 'fa-light',
                'icon'       => 'fa-arrow-right',
                'label'      => 'More info'
            ]));
        }
        if ($this->externalLink) {
            array_push($links, new Link([
                'label'      => $this->externalLink['title'] ?? 'Read more',
                'href'       => $this->externalLink['url'],
                'target'     => $this->externalLink['target'] ?? null,
                'iconPrefix' => 'fa-regular',
                'icon'       => 'fa-arrow-up-right-from-square'
            ]));
        }
        if (!empty($links)) {
            array_push($transformedInnerComponents,
                new LinkGroup(
                    ['isNested' => true, 'context' => $this->get_bem_prefix(), 'layout' => GroupLayout::INLINE],
                    $links
                )
            );
        }

        $content = new Group(
            ['context' => $this->get_bem_prefix(), 'shortName' => 'content'],
            array(
                new Heading(['context' => "{$this->get_bem_prefix()}__content", 'level' => 3], $this->name),
                ...$transformedInnerComponents
            )
        );

        $this->dateComponent?->update_context($this->get_bem_prefix());

        $this->innerComponents = $this->dateComponent ? [$this->dateComponent, $content] : [$content];
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        // The context isn't available in the constructor if it was updated by the parent EventList (if it happens to be in one),
        // so we need to make some adjustments here
        if ($this->get_context() === 'events__list') {
            $this->tagName = Tag::LI;
        }

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
