<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

/**
 * Class to manage the data for Events CPT archives
 * (Display templates are handled in the TemplateHandler class)
 */
class Calendar {
    public function __construct() {
        add_action('pre_get_posts', [$this, 'sort_events_in_admin']);
        add_action('pre_get_posts', [$this, 'customise_default_event_archive']);
        add_action('pre_get_posts', [$this, 'handle_date_archives']);
    }

    private function is_events_query($query): bool {
        return $query->get('post_type') === 'event';
    }

    private function sort_by_event_date(&$query): void {
        $query->set('meta_key', 'sort_date');
        $query->set('order', 'DESC');
        $query->set('meta_type', 'DATE');
        $query->set('orderby', 'meta_value');
    }

    public function sort_events_in_admin($query): object {
        if (!is_admin() || !$this->is_events_query($query)) {
            return $query;
        }

        $this->sort_by_event_date($query);

        return $query;
    }

    /**
     * Alter the default query for the CPT archive to only show past events
     * (Upcoming to be handled in the template with its own query, allowing past events to use WP default pagination)
     *
     * @param  $query  - The current WP_Query instance
     *
     * @return mixed
     */
    public function customise_default_event_archive($query): object {
        if (is_admin() || !$this->is_events_query($query) || !$query->is_main_query()) {
            return $query;
        }

        $this->sort_by_event_date($query);

        // If the option to show past events is disabled, return no results
        if (get_option('options_show_past_events') === 'never') {
            $query->set('post__in', []);

            return $query;
        }

        if (get_option('options_show_past_events') === 'current_year') {
            $query->set('meta_query', array(
                'relation' => 'AND',
                array(
                    'key'     => 'sort_date',
                    'value'   => array(date('Y') . '-01-01', date('Y') . '-12-31'),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
            ));

            return $query;
        }

        if (get_option('options_show_past_events') === 'past_year') {
            $today = current_time('Y-m-d');
            $oneYearAgo = date('Y-m-d', strtotime('-1 year', strtotime($today)));

            $query->set('meta_query', array(
                'relation' => 'AND',
                array(
                    'key'     => 'sort_date',
                    'value'   => [$oneYearAgo, $today],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
            ));

            return $query;
        }

        // Otherwise, just filter out past events (should match "always" and there being no setting, or an invalid one)
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key'     => 'sort_date',
                'value'   => current_time('Y-m-d'),
                'compare' => '<',
                'type'    => 'DATE',
            ),
        ));

        return $query;
    }

    public function handle_date_archives($query): object {
        if (is_admin() || !$this->is_events_query($query)) {
            return $query;
        }
        if (!$query->is_date()) {
            return $query;
        }

        return $query;
    }
}
