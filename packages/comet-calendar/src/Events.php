<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\WordPress\Calendar;
use DateTime;

/**
 * Class to register and manage the data for the Events custom post type
 * (Display templates are handled in the TemplateHandler class)
 */
class Events {

    public function __construct() {
        add_action('init', [$this, 'create_event_cpt'], 15);
        add_action('init', [$this, 'register_custom_permalink_placeholder'], 15);
        add_filter('post_type_link', [$this, 'populate_custom_permalink'], 10, 2);
        add_action('pre_get_posts', [$this, 'customise_event_archive']);

        add_filter('manage_event_posts_columns', [$this, 'add_admin_list_columns'], 20);
        add_filter('manage_event_posts_custom_column', [$this, 'populate_admin_list_columns'], 30, 2);

        // Add a common date field to use for query filtering and sorting
        add_action('acf/save_post', [$this, 'save_common_event_date'], 20);

        // Misc
        add_filter('doublee_enable_page_behaviour_options_for_post_types', fn($post_types) => ['event', ...$post_types]);
    }

    /**
     * Create the custom post type
     *
     * @return void
     */
    public function create_event_cpt(): void {
        $page_title = get_option('options_events_page_title');
        $title = !empty($page_title) ? $page_title : __('Events', 'comet');
        $slug = strtolower(sanitize_title($title)) ?? 'events';

        $labels = array(
            'name'                  => _x($title, 'Post Type General Name', 'comet'),
            'singular_name'         => _x('Event', 'Post Type Singular Name', 'comet'),
            'menu_name'             => __('Events', 'comet'),
            'name_admin_bar'        => __('Event', 'comet'),
            'archives'              => __($title, 'comet'),
            'attributes'            => __('Event Attributes', 'comet'),
            'parent_item_colon'     => __('Parent Event:', 'comet'),
            'all_items'             => __('Events', 'comet'),
            'add_new_item'          => __('Add New Event', 'comet'),
            'add_new'               => __('Add New Event', 'comet'),
            'new_item'              => __('New Event', 'comet'),
            'edit_item'             => __('Edit Event', 'comet'),
            'update_item'           => __('Update Event', 'comet'),
            'view_item'             => __('View Event', 'comet'),
            'view_items'            => __('View Events', 'comet'),
            'search_items'          => __('Search Events', 'comet'),
            'not_found'             => __('Not found', 'comet'),
            'not_found_in_trash'    => __('Not found in Trash', 'comet'),
            'featured_image'        => __('Event poster', 'comet'),
            'set_featured_image'    => __('Set featured image', 'comet'),
            'remove_featured_image' => __('Remove featured image', 'comet'),
            'use_featured_image'    => __('Use as featured image', 'comet'),
            'insert_into_item'      => __('Insert into event', 'comet'),
            'uploaded_to_this_item' => __('Uploaded to this Event', 'comet'),
            'items_list'            => __('Events list', 'comet'),
            'items_list_navigation' => __('Events list navigation', 'comet'),
            'filter_items_list'     => __('Filter items list', 'comet'),
        );
        $rewrite = array(
            'slug'       => $slug . '/%year%', // Placeholder handled by populate_custom_permalink_rewrite
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        );
        $args = array(
            'label'               => __('Event', 'comet'),
            'description'         => __('Events', 'comet'),
            'labels'              => $labels,
            'rewrite'             => $rewrite,
            'supports'            => array('title', 'editor', 'thumbnail', 'revisions'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 10,
            'menu_icon'           => 'dashicons-calendar-alt',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => $slug,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
            'show_in_rest'        => true, // disables block editor
        );

        register_post_type('event', $args);
        flush_rewrite_rules();
    }

    public function register_custom_permalink_placeholder(): void {
        add_rewrite_tag('%year%', '([0-9]{4})');
    }

    public function populate_custom_permalink($post_link, $post) {
        if (is_object($post) && $post->post_type == 'event') {
            $event_date = get_post_meta($post->ID, 'start_date', true);
            $post_date = get_the_date('d-m-Y', $post);
            $date = new DateTime($event_date ? $event_date : $post_date);
            $year = $date->format('Y');

            return str_replace(array('%year%'), $year, $post_link);
        }

        return $post_link;
    }

    /**
     * Alter the default query for the CPT archive to only show past events
     * (Upcoming to be handled in the template with its own query, allowing past events to use WP default pagination)
     *
     * @param  $query
     *
     * @return mixed
     */
    public function customise_event_archive($query): mixed {
        if (is_post_type_archive('event') && isset($query->query['post_type']) && $query->query['post_type'] === 'event') {
            if ($query->is_main_query() && !is_admin()) {
                $query->set('meta_key', 'sort_date');
                $query->set('order', 'DESC');
                $query->set('meta_type', 'DATE');
                $query->set('orderby', 'meta_value');

                $query->set('meta_query', array(
                    'relation' => 'OR',
                    array(
                        // Filter out upcoming events
                        'key'     => 'sort_date',
                        'value'   => current_time('Y-m-d'),
                        'compare' => '<',
                        'type'    => 'DATE',
                    ),
                ));
            }
        }

        return $query;
    }

    /**
     * Add custom columns to the admin list
     *
     * @param  $columns
     *
     * @return array
     */
    public function add_admin_list_columns($columns): array {
        $checkbox = array_slice($columns, 0, 1, true);
        $one = array_slice($columns, 0, (array_search('title', array_keys($columns))) + 1, true);
        $two = array_diff($columns, $one);

        // Remove the post date column
        unset($two['date']);

        // TODO: Improve this
        $sortColTooltip = <<<HTML
		<span tabindex="0" title="Set automatically from the first date. Refresh the page after editing dates to see this updated.">?</span>
		HTML;

        return array_merge(
            $checkbox,
            $one,
            array(
                'hacky_extra'   => __('', 'comet'),
                'sort_date'     => __("Sorted as $sortColTooltip", 'comet'),
                'event_date'    => __('Event date', 'comet'),
                'location'      => __('Location', 'comet'),
                'external_link' => __('External link', 'comet'),
            ),
            $two
        );
    }

    /**
     * Populate the custom columns in the admin list
     *
     * @param  $column_name
     * @param  $post_id
     *
     * @return void
     * @throws \DateMalformedStringException
     */
    public function populate_admin_list_columns($column_name, $post_id): void {

        if ($column_name === 'sort_date') {
            $value = get_post_meta($post_id, 'sort_date', true);
            if (!empty($value)) {
                $date = new DateTime($value);
                echo $date->format('Y-m-d');
            }
        }
    }

    /**
     * Save the common event date to post meta for query sorting and filtering
     *
     * @param  $post_id
     *
     * @return void
     */
    public function save_common_event_date($post_id): void {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST['acf'])) return;
        if (get_post_type($post_id) !== 'event') return;

        $current_date_type = get_post_meta($post_id, 'type', true);
        $date = match ($current_date_type) {
            'single'         => get_post_meta($post_id, 'single_date', true),
            'range'          => get_post_meta($post_id, 'range_start_date', true),
            'multi'          => get_post_meta($post_id, 'multi_dates_0_date', true),
            'multi_extended' => get_post_meta($post_id, 'multi_extended_0_date', true),
            default          => '',
        };

        update_post_meta($post_id, 'sort_date', $date);
    }

}
