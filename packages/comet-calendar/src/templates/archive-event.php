<?php
/**
 * I usually believe display is the responsibility of the theme, but because the plugin is used to provide event-related blocks
 * (which is logical to avoid over-coupling this plugin with the Comet Canvas theme),
 * it also makes sense to include default archive and single event templates here too.
 */

use Doubleedesign\Comet\Core\{EventCard, EventList, Separator};
use Doubleedesign\Comet\WordPress\Calendar\{TemplateHandler};

get_header();
get_template_part('template-parts/page-header');

/** ===================================================================================================
 * Upcoming events
 * ================================================================================================= */
$events = TemplateHandler::get_upcoming_event_ids(100);
if($events) {
	$cards = array_map(function($eventId) {
		if(get_post_meta('sort_date', $eventId, true) !== '') { // Skip events without dates
			$title = get_the_title($eventId);
			$detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink($eventId) : null;
			$location = get_field('location', $eventId);
			$externalLink = get_field('external_link', $eventId);
			$dateComponent = TemplateHandler::get_date_block($eventId);

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
		'heading'    => 'Upcoming Events',
		'size'       => 'wide',
		'colorTheme' => 'secondary',
		'maxPerRow'  => 3
	], $filtered_cards);
	$component->render();
}

/** ===================================================================================================
 * Separator
 * ================================================================================================= */
$separator = new Separator([
    'size'       => 'narrow',
    'colorTheme' => 'accent',
    'lineStyle'  => apply_filters('comet_calendar_event_archive_separator_style', 'dots')
]);
$separator->render();

/** ===================================================================================================
 * Past events
 * ================================================================================================= */
if (have_posts()) {
    $cards = [];
    while (have_posts()) {
        the_post();
        if (get_post_meta('sort_date', get_the_ID(), true) !== '') { // Skip events without dates
            $title = get_the_title();
            $detailUrl = get_option('options_enable_event_detail_pages') ? get_the_permalink() : null;
            $location = get_field('location');
            $externalLink = get_field('external_link');
            $dateComponent = TemplateHandler::get_date_block(get_the_ID());

            array_push($cards, new EventCard([
                'dateComponent' => $dateComponent,
                'name'          => $title,
                'detailUrl'     => $detailUrl,
                'externalLink'  => $externalLink,
                'location'      => $location
            ]));
        }
    }

    $component = new EventList([
        'heading'    => 'Past Events',
        'size'       => 'contained',
        'maxPerRow'  => 2,
        'colorTheme' => 'dark'
    ], $cards);
    $component->render();

    // TODO: Implement pagination
}

get_footer();
