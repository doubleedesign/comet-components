<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

use DateTime;

class Events {

	public function __construct() {
		add_action('init', [$this, 'create_event_cpt'], 15);
		add_action('init', [$this, 'register_custom_permalink_placeholder'], 15);
		add_filter('post_type_link', [$this, 'populate_custom_permalink'], 10, 2);
		add_action('acf/update_field_group', [$this, 'save_acf_fields_to_plugin'], 1, 1);
		add_action('pre_get_posts', [$this, 'customise_event_archive']);
		add_action('get_user_option_meta-box-order_event', [$this, 'metabox_order']);
		add_filter('manage_event_posts_columns', [$this, 'add_admin_list_columns'], 20);
		add_filter('manage_event_posts_custom_column', [$this, 'populate_admin_list_columns'], 30, 2);
		add_action('add_meta_boxes', [$this, 'remove_yoast_metabox'], 100);
	}

	/**
	 * Create the custom post type
	 * @return void
	 */
	function create_event_cpt(): void {
		$labels = array(
			'name'                  => _x('Events', 'Post Type General Name', 'comet'),
			'singular_name'         => _x('Event', 'Post Type Singular Name', 'comet'),
			'menu_name'             => __('Events', 'comet'),
			'name_admin_bar'        => __('Event', 'comet'),
			'archives'              => __('Events', 'comet'),
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
			'slug'       => 'events/%year%', // Placeholder handled by populate_custom_permalink_rewrite
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
			'has_archive'         => 'events',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true, // disables block editor
		);

		register_post_type('event', $args);
	}


	function register_custom_permalink_placeholder(): void {
		add_rewrite_tag('%year%', '([0-9]{4})');
	}

	function populate_custom_permalink($post_link, $post) {
		if(is_object($post) && $post->post_type == 'event') {
			$event_date = get_post_meta($post->ID, 'start_date', true);
			$post_date = get_the_date('d-m-Y', $post);
			$date = new DateTime($event_date ? $event_date : $post_date);
			$year = $date->format('Y');
			return str_replace(array('%year%'), $year, $post_link);
		}
		return $post_link;
	}


	/**
	 * Override the save location for ACF JSON files for field groups set to be shown on this CPT
	 *
	 * @param $group
	 *
	 * @return void
	 */
	function save_acf_fields_to_plugin($group): void {
		// Flatten all location rules into a single-dimensional array
		$locations = call_user_func_array('array_merge', $group['location']);
		// Check if the locations include this CPT
		$is_shown_on_cpt = array_filter($locations, function($location) {
			return $location['param'] === 'post_type' && $location['value'] === 'event';
		});

		if($is_shown_on_cpt) {
			Fields::override_acf_json_save_location();
		}
	}


	/**
	 * Alter the default query for the CPT archive
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	function customise_event_archive($query): mixed {
		if(is_post_type_archive('event') && isset($query->query['post_type']) && $query->query['post_type'] === 'event') {
			if($query->is_main_query() && !is_admin()) {
				//$query->set('meta_key', 'start_date');
				//$query->set('orderby', 'meta_value');$query->set('orderby', 'post_date');
				$query->set('order', 'DESC');
				$query->set('meta_type', 'DATE');

				// Filter out upcoming events because they will be displayed separately in the template
				$query->set('meta_query', array(
					'relation' => 'OR',
					array(
						'key'     => 'start_date',
						'value'   => current_time('Y-m-d'),
						'compare' => '<',
						'type'    => 'DATE'
					),
					// But include events without a start_date
					array(
						'key'     => 'start_date',
						'compare' => 'NOT EXISTS',
					),
				));
			}
		}

		return $query;
	}


	/**
	 * By default, show the featured image metabox above everything except the Publish metabox
	 * for this CPT
	 * @return array
	 */
	function metabox_order(): array {

		return array(
			'side' => join(
				",",
				array(
					'submitdiv',
					'postimagediv'
				)
			),
		);
	}


	/**
	 * Add custom columns to the admin list
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	function add_admin_list_columns($columns): array {
		$checkbox = array_slice($columns, 0, 1, true);
		$one = array_slice($columns, 0, (array_search('title', array_keys($columns))) + 1, true);
		$two = array_diff($columns, $one);

		return array_merge(
			$checkbox,
			$one,
			array(
				'event_date' => __('Event date', 'comet'),
				'location'   => __('Location', 'comet'),
			),
			$two
		);
	}


	/**
	 * Populate the custom columns in the admin list
	 *
	 * @param $column_name
	 * @param $post_id
	 *
	 * @return void
	 */
	function populate_admin_list_columns($column_name, $post_id): void {
		if($column_name === 'event_date') {
			$raw_date = get_post_meta($post_id, 'start_date', true);
			if($raw_date) {
				$date_object = DateTime::createFromFormat('Ymd', $raw_date);
				$formatted_date = $date_object->format('d F Y');

				echo $formatted_date;
			}
		}

		if($column_name === 'location') {
			echo get_post_meta($post_id, 'location', true);
		}
	}


	/**
	 * Don't show Yoast SEO on Event edit screen
	 * @return void
	 */
	function remove_yoast_metabox(): void {
		remove_meta_box('wpseo_meta', 'event', 'normal');
	}


	/**
	 * Utility function to get the IDs of the next X upcoming events
	 * @param $qty
	 * @return array
	 */
	public static function get_upcoming_event_ids($qty): array {
		$today = current_time('Y-m-d');
		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => $qty,
			'meta_key'       => 'start_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'start_date',
					'value'   => $today,
					'compare' => '>=',
					'type'    => 'DATE'
				)
			)
		);

		$query = new WP_Query($args);

		return wp_list_pluck($query->posts, 'ID');
	}

}
