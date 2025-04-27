<?php /** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\WordPress\Calendar\Events;
use Doubleedesign\Comet\Core\{EventList, EventCard};

$heading = $block['data']['heading'] ?? 'Upcoming Events';
$perRow = $block['data']['max_items_per_row'] ?? 3;
$totalItems = $block['data']['total_items'] ?? 3;
$events = Events::get_upcoming_event_ids($totalItems);
$colorTheme = $block['colorTheme'] ?? null;

$cards = array_map(function($eventId) use ($colorTheme) {
	if(get_post_meta('sort_date', $eventId, true) !== '') { // Skip events without dates
		$title = get_the_title($eventId);
		$detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink($eventId) : null;
		$location = get_field('location', $eventId);
		$externalLink = get_field('external_link', $eventId);
		$dateComponent = Events::get_date_block($eventId, $colorTheme);

		return new EventCard([
			'dateComponent' => $dateComponent,
			'name'          => $title,
			'detailUrl'     => $detailUrl,
			'externalLink'  => $externalLink,
			'location'      => $location
		]);
	}

	return null;
}, $events);

$filtered_cards = array_filter($cards, function($card) {
	return $card !== null;
});

$component = new EventList([
	'heading'        => $heading,
	'maxItemsPerRow' => $perRow,
], $filtered_cards);
$component->render();
