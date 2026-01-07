<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @var array $block */

use Doubleedesign\Comet\Core\{EventCard, EventList};
use Doubleedesign\Comet\WordPress\Calendar\{Events};

// $inEditorContext = isset($block['mode']) && $block['mode'] === 'preview';
$events = Events::get_upcoming_event_ids($block['itemCount'] ?? 3);

$cards = array_map(function($eventId) use ($block) {
    if (get_post_meta('sort_date', $eventId, true) !== '') { // Skip events without dates
        $title = get_the_title($eventId);
        $detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink($eventId) : null;
        $location = get_field('location', $eventId);
        $externalLink = get_field('external_link', $eventId);
        $dateComponent = Events::get_date_block($eventId, $block['colorTheme'] ?? 'primary');

        return new EventCard([
            'dateComponent' => $dateComponent,
            'name'          => $title,
            'detailUrl'     => $detailUrl,
            'externalLink'  => !empty($externalLink) ? $externalLink : null,
            'location'      => $location
        ]);
    }

    return null;
}, $events);

$filtered_cards = array_filter($cards, function($card) {
    return $card !== null;
});

$component = new EventList([
    'colorTheme'             => $block['colorTheme'] ?? 'primary',
    'size'                   => $block['size'] ?? 'contained',
    'hAlign'                 => $block['hAlign'] ?? 'start',
    'heading'                => get_field('heading') ?? 'Upcoming Events',
    'maxPerRow'              => $block['maxPerRow'] ?? 3,
    'itemCount'              => $block['itemCount'] ?? 3,
    'viewAllUrl'             => get_post_type_archive_link('event')
], $filtered_cards);
$component->render();
