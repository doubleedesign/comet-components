<?php
/**
 * I usually believe display is the responsibility of the theme, but because the plugin is used to provide event-related blocks
 * (which is logical to avoid over-coupling this plugin with the Comet Canvas theme),
 * it also makes sense to include default archive and single event templates here too.
 */
use Doubleedesign\Comet\Core\{PageHeader, Container, EventList, EventCard, DateBlock, DateRangeBlock};
use Doubleedesign\Comet\WordPress\PreprocessedHTML;

get_header();

$object = get_queried_object();

if(class_exists('Doubleedesign\Breadcrumbs\Breadcrumbs')) {
	$breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_raw_breadcrumbs();
	$pageHeader = new PageHeader(['size' => 'narrow'], $object->label, $breadcrumbs);
}
else {
	$pageHeader = new PageHeader(['size' => 'narrow'], $object->label);
}

$pageHeader->render();


function get_date_block($event_id): DateBlock|DateRangeBlock|null {
	$type = get_field('type', $event_id);
	$dateComponent = null;
	switch($type) {
		case 'single':
			$rawDate = get_post_meta($event_id, 'single_date', true);
			$formattedDate = (new DateTime($rawDate))->format('Y-m-d');
			$dateComponent = new DateBlock(['date' => $formattedDate, 'showDay' => true]);
			break;
		case 'range':
			$rawStartDate = get_post_meta($event_id, 'range_start_date', true);
			$rawEndDate = get_post_meta($event_id, 'range_end_date', true);
			$startDate = (new DateTime($rawStartDate))->format('Y-m-d');
			$endDate = (new DateTime($rawEndDate))->format('Y-m-d');
			$sortDate = get_post_meta($event_id, 'sort_date', true);
			// is the sort date in the past? If so, show the year. For upcoming dates, don't show the year
			$showYear = $sortDate && $sortDate < (new DateTime())->format('Ymd');
			$dateComponent = new DateRangeBlock([
				'showDay'    => !$showYear,
				'showYear'   => $showYear,
				'start_date' => $startDate,
				'end_date'   => $endDate,
			]);
			break;
		default:
			break;
	}

	return $dateComponent;
}

// Upcoming
$events = Doubleedesign\Comet\WordPress\Calendar\Events::get_upcoming_event_ids(100);
$cards = array_map(function($eventId) {
	if(get_post_meta('sort_date', $eventId, true) !== '') { // Skip events without dates
		$title = get_the_title($eventId);
		$detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink($eventId) : null;
		$location = get_field('location', $eventId);
		$externalLink = get_field('external_link', $eventId);
		$dateComponent = get_date_block($eventId);

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

$container = new Container(['size' => 'wide', 'backgroundColor' => 'dark'], [
	new EventList([
		'heading'        => 'Upcoming Events',
		'maxItemsPerRow' => 3
	], $filtered_cards),
]);
$container->render();
// Archive of past events
if(have_posts()) {
	$cards = [];
	while(have_posts()) {
		the_post();
		if(get_post_meta('sort_date', get_the_ID(), true) !== '') { // Skip events without dates
			$title = get_the_title();
			$detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink() : null;
			$location = get_field('location');
			$externalLink = get_field('external_link');
			$dateComponent = get_date_block(get_the_ID());

			array_push($cards, new EventCard([
				'dateComponent' => $dateComponent,
				'name'          => $title,
				'detailUrl'     => $detailUrl,
				'externalLink'  => $externalLink,
				'location'      => $location
			]));
		}
	}

	ob_start();
	the_posts_pagination();
	$pagination = ob_get_clean();

	$container = new Container(['size' => 'narrow', 'backgroundColor' => 'white'], [
		new EventList([
			'heading'        => 'Past Events',
			'maxItemsPerRow' => get_option('options_events_per_row')
		], $cards),
		new PreprocessedHTML([], $pagination)
	]);
	$container->render();
}

get_footer();
