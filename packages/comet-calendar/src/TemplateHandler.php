<?php
namespace Doubleedesign\Comet\WordPress\Calendar;
use DateTime;
use Doubleedesign\Comet\Core\{DateBlock, DateRangeBlock};

class TemplateHandler {

    public function __construct() {
        add_filter('template_include', [$this, 'event_archive_template']);
        add_filter('template_include', [$this, 'event_single_template']);
        add_filter('template_redirect', [$this, 'maybe_redirect_single_event'], 20);
    }

    public function event_archive_template($template) {
        if (!is_post_type_archive('event')) return $template;

        $theme_template = locate_template('archive-event.php');
        if ($theme_template) {
            return $theme_template;
        }
        else {
            $plugin_template = plugin_dir_path(__FILE__) . 'templates/archive-event.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        // Return the default template if override conditions aren't met
        return $template;
    }

    public function event_single_template($template) {
        if (!is_singular('event')) return $template;

        $theme_template = locate_template('single-event.php');
        if ($theme_template) {
            return $theme_template;
        }
        else {
            $plugin_template = plugin_dir_path(__FILE__) . 'templates/single-event.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        // Return the default template if override conditions aren't met
        return $template;
    }

    public function maybe_redirect_single_event(): void {
        if (!is_singular('event')) return;
        if (get_option('enable_event_detail_pages') == null) return;

        //        $event_detail_setting = get_option('enable_event_detail_pages');
        //        if ($event_detail_setting === 'always') {
        //            return;
        //        }
        //
        //        if ($event_detail_setting === 'never') {
        //            wp_redirect(get_post_type_archive_link('event'));
        //            exit;
        //        }
        //
        //        if ($event_detail_setting === 'past_year') {
        //
        //        }
        //
        //        if ($event_detail_setting === 'current_year') {
        //
        //        }

    }

    /**
     * Utility function to get the IDs of the next X upcoming events
     *
     * @param  $qty
     *
     * @return array
     */
    public static function get_upcoming_event_ids($qty): array {
        $today = current_time('Y-m-d');
        $args = array(
            'post_type'      => 'event',
            'posts_per_page' => $qty,
            'meta_key'       => 'sort_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'sort_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                )
            )
        );

        $query = new \WP_Query($args);

        return wp_list_pluck($query->posts, 'ID');
    }

    public static function get_date_block(int $event_id, ?string $colorTheme = null): DateBlock|DateRangeBlock|null {
        $type = get_post_meta($event_id, 'type', true);
        $dateComponent = null;
        $sortDate = get_post_meta($event_id, 'sort_date', true);
        // is the sort date in the past? If so, show the year. For upcoming dates, don't show the year
        $isUpcoming = $sortDate && $sortDate >= (new DateTime())->format('Ymd');
        switch ($type) {
            case 'single':
                $rawDate = get_post_meta($event_id, 'single_date', true);
                $formattedDate = (new DateTime($rawDate))->format('Y-m-d');
                $dateComponent = new DateBlock([
                    'date'       => $formattedDate,
                    'showDay'    => $isUpcoming,
                    'showYear'   => !$isUpcoming,
                    'colorTheme' => $colorTheme ?? ($isUpcoming ? 'secondary' : 'dark')
                ]);
                break;
            case 'range':
                $rawStartDate = get_post_meta($event_id, 'range_start_date', true);
                $rawEndDate = get_post_meta($event_id, 'range_end_date', true);
                $startDate = (new DateTime($rawStartDate))->format('Y-m-d');
                $endDate = (new DateTime($rawEndDate))->format('Y-m-d');
                $dateComponent = new DateRangeBlock([
                    'showDay'    => $isUpcoming,
                    'showYear'   => !$isUpcoming,
                    'startDate'  => $startDate,
                    'endDate'    => $endDate,
                    'colorTheme' => $colorTheme ?? ($isUpcoming ? 'secondary' : 'dark')
                ]);
                break;
                // FIXME: Handle multi-date type
            default:
                break;
        }

        return $dateComponent;
    }

}
