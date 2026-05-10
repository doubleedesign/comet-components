<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @var array $block */

use Doubleedesign\Comet\Core\{EventCard, EventList, Utils};
use Doubleedesign\Comet\WordPress\Calendar\{TemplateHandler};

// $inEditorContext = isset($block['mode']) && $block['mode'] === 'preview';
$events = TemplateHandler::get_upcoming_event_ids($block['itemCount'] ?? 3);
$cards = array_map(function($eventId) use ($block) {
    if (get_post_meta('sort_date', $eventId, true) !== '') { // Skip events without dates
        $title = get_the_title($eventId);
        $detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink($eventId) : null;
        $location = get_post_meta($eventId, 'location', true);
        $externalLink = get_post_meta($eventId, 'external_link', true);
        $dateComponent = TemplateHandler::get_date_block($eventId, $block['colorTheme'] ?? 'primary');

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

$attributes = Utils::array_pick($block, ['colorTheme', 'size', 'hAlign', 'layout', 'maxPerRow', 'itemCount']);

$component = new EventList([
	...$attributes,
    'heading'                => !empty($block['data']['heading']) ? $block['data']['heading'] : 'Upcoming Events',
    'viewAllUrl'             => get_post_type_archive_link('event')
], $filtered_cards);
$component->render();
