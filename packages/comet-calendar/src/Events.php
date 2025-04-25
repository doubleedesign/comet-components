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

		// Customisation of list in admin, including the quick add form and inline editing
		add_action('admin_head', 'acf_form_head', 5);
		add_filter('views_edit-event', [$this, 'display_quick_add_form'], 11);
		add_action('admin_notices', array($this, 'display_quick_add_success_message'), 10);
		add_filter('manage_event_posts_columns', [$this, 'add_admin_list_columns'], 20);
		add_filter('manage_event_posts_custom_column', [$this, 'populate_admin_list_columns'], 30, 2);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_js'], 5);
		add_action('acf/save_post', [$this, 'handle_inline_acf_form_submit'], 11);
		add_action('acf/save_post', [$this, 'handle_acf_quick_add_form_submit'], 20);

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
	 * Add an ACF form at the top of the Events list in the admin
	 * Note: This requires some JS to aid handling or we get a white screen on save, see admin.js
	 * @param $views
	 * @return mixed
	 */
	function display_quick_add_form($views): mixed {

		// Copy as much of the HTML structure/classes etc from ACF post boxes so we get the same styling
		$headerHtml = <<<HTML
		<div class="postbox-header">
			<h2>Quick Add</h2>
			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text">Toggle panel: Quick Add</span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>
		</div>
		HTML;

		echo '<div id="poststuff">';
		echo '<div class="admin-quick-add postbox acf-postbox">';
		echo $headerHtml;
		acf_form(array(
			'post_id'           => 'new_post',
			'post_title'        => true,
			'post_content'      => false,
			'new_post'          => array(
				'post_type'   => 'event',
				'post_status' => 'publish'
			),
			'form'              => true,
			'form_attributes'   => array(
				'method' => 'post'
			),
			'ajax'              => true,
			'html_after_fields' => '<button class="button cancel" type="reset">Cancel</button>',
			'submit_value'      => 'Add event'
		));
		echo '</div>';
		echo '</div>';

		return $views;
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
				'hacky_extra' => __('', 'comet'),
				'event_date'  => __('Event date', 'comet'),
				'location'    => __('Location', 'comet'),
			),
			$two
		);
	}

	private function display_wrapped_acf_form($formId, $postId, $fields): void {
		echo <<<HTML
		<div class="row-actions">
			<span class="inline hide-if-no-js">
				<button class="button-link button-link--acf" aria-controls="$formId">Quick edit</button>
			</span>
		</div>
		<div class="admin-column-acf-form" data-form-id="$formId">
		HTML;

		acf_form(array(
			'id'                 => $formId,
			'post_id'            => $postId,
			'form'               => true,
			'form_attributes'    => array(
				'method' => 'post'
			),
			'fields'             => $fields,
			'html_before_fields' => '<div class="acf-spinner"></div>',
			'html_after_fields'  => '<button class="button cancel" type="reset">Cancel</button>',
			'ajax'               => true, // Note: ACF's AJAX doesn't work in this context, see handle_inline_acf_form_submit() and admin.js for handling
			'return'             => ''
		));

		echo <<<HTML
		</div>
		HTML;

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

		/**
		 * Using acf_form() here is not a standard/expected use of it, and the post list itself is also a form
		 * which makes adding the ACF forms inside less than ideal semantically but much easier than something more custom.
		 * But for some unknown reason, only the second and subsequent ACF forms are actually <form>s,
		 * so this one is here as a hidden decoy to make the real ones for location and date work.
		 */
		if($column_name === 'hacky_extra') {
			echo '<div style="display:none;">';
			acf_form(array(
				'id'      => 'acf-form-decoy',
				'post_id' => $post_id,
				'fields'  => array('not_a_real_field'),
				'form'    => true,
				'ajax'    => false,
				'return'  => ''
			));
			echo '</div>';
		}

		if($column_name === 'event_date') {
			// Date type is a select list with values that should match up to the names of the groups that contain the detailed data
			$date_type = get_field('type');
			$date_data = get_field($date_type);
			$field = get_field_object($date_type, $post_id);
			$field_key = $field['key'];

			$output = '';
			switch($date_type) {
				case 'single':
					$output = $date_data['date'];
					break;
				case 'range':
					$output = $date_data['start_date'] . ' - ' . $date_data['end_date'];
					break;
				case 'multi':
					foreach($date_data['dates'] as $date) {
						$output .= $date['date'] . '<br>';
					}
					break;
				case 'multi_extended':
					foreach($date_data as $date) {
						$output .= $date['date'] . '<br>';
					}
					break;
			}

			echo <<<HTML
			<span class="acf-field-value" data-field-key="$field_key" data-post-id="$post_id">$output</span>
			HTML;

			$form_id = 'acf-form-event-date-' . $post_id;
			$this->display_wrapped_acf_form($form_id, $post_id, ['type', 'single', 'range', 'multi', 'multi_extended']);
		}

		if($column_name === 'location') {
			$field = get_field_object('location', $post_id);
			$field_key = $field['key'];
			$value = get_post_meta($post_id, 'location', true);

			// Display the field value wrapped in ID and field key identifiers so the JS can update it when inline edits are saved
			echo <<<HTML
			<span class="acf-field-value" data-field-key="$field_key" data-post-id="$post_id">$value</span>
			HTML;

			$form_id = 'acf-form-location-' . $post_id;
			$this->display_wrapped_acf_form($form_id, $post_id, ['location']);
		}
	}


	/**
	 * Enqueue the JS for the admin page custom form handling
	 * @return void
	 */
	function enqueue_admin_js(): void {
		$js_path = plugin_dir_url(__FILE__) . 'assets/admin.js';
		wp_enqueue_script('comet-calendar-admin', $js_path, array(), COMET_CALENDAR_VERSION, true);
	}

	/**
	 * Additional handling for the AJAX form submission from the inline ACF forms in the admin list
	 * ACF takes care of the actual data save, this just sends a JSON response back to the JavaScript rather than the whole page HTML
	 * @param $post_id
	 * @return void
	 */
	function handle_inline_acf_form_submit($post_id): void {
		if(isset($_POST['custom_acf_inline_form']) && $_POST['custom_acf_inline_form'] === 'true') {
			wp_send_json_success([
				'post_id' => $post_id,
				'fields'  => $_POST['acf'] ?? [],
			]);

			wp_die();
		}
	}

	/**
	 * Additional handling for the AJAX form submission from the ACF quick add form in the admin list
	 * ACF takes care of the actual data save, this just sends a JSON response back to the JavaScript rather than the whole page HTML
	 * @param $post_id
	 * @return void
	 */
	function handle_acf_quick_add_form_submit($post_id): void {
		if(isset($_POST['custom_acf_quick_add_form']) && $_POST['custom_acf_quick_add_form'] === 'true') {
			// We only have access to the submitted data here, not the resulting post ID, but we can infer it with a query for the most recently added event
			// Note: The \ before WP_Query is very important, otherwise it looks for that class in the Comet Calendar namespace
			$query = new \WP_Query(array(
				'post_type'      => 'event',
				'posts_per_page' => 1,
				'orderby'        => 'post_date',
				'order'          => 'DESC',
			));
			$post_id = $query->posts[0]->ID;
			wp_send_json_success([
				'post_id' => $post_id,
			]);

			wp_die();
		}
	}

	/**
	 * Display a success message after the quick add form is submitted and returns successfully
	 * @return void
	 */
	function display_quick_add_success_message(): void {
		if(!isset($_GET['post_type']) || $_GET['post_type'] !== 'event') return;
		if(!isset($_GET['added'])) return;

		$post_id = $_GET['added'];
		$post_title = get_the_title($post_id);

		echo <<<HTML
		<div class="notice notice-success is-dismissible comet-quick-add-success">
			<p>Event "$post_title" added successfully.</p>
		</div>
		HTML;

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
