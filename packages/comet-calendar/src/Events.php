<?php /** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\WordPress\Calendar;
use Doubleedesign\Comet\Core\{DateBlock, DateRangeBlock};
use DateTime;
use Exception;

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

		// Add a common date field to use for query filtering and sorting
		add_action('acf/save_post', [$this, 'save_common_event_date'], 20);

		// Misc
		add_action('add_meta_boxes', [$this, 'remove_yoast_metabox'], 100);
	}

	/**
	 * Create the custom post type
	 * @return void
	 */
	function create_event_cpt(): void {
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
	 * Alter the default query for the CPT archive to only show past events
	 * (Upcoming to be handled in the template with its own query, allowing past events to use WP default pagination)
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	function customise_event_archive($query): mixed {
		if(is_post_type_archive('event') && isset($query->query['post_type']) && $query->query['post_type'] === 'event') {
			if($query->is_main_query() && !is_admin()) {
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
	 * Add an ACF form at the top of the Events list in the admin
	 * Note: This requires some JS to aid handling or we get a white screen on save, see admin.js
	 * @param $views
	 * @return mixed
	 */
	function display_quick_add_form($views): mixed {

		// Copy as much of the HTML structure/classes etc. from ACF post meta boxes so we get the same styling
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
			'id'                => 'acf-form-quick-add',
			'post_id'           => 'new_post',
			'post_title'        => true,
			'post_content'      => false,
			'new_post'          => array(
				'post_type'   => 'event',
				'post_status' => 'publish'
			),
			'form'              => true,
			'form_attributes'   => array(
				'method' => 'post',
			),
			'ajax'              => true, // Note: ACF's AJAX doesn't fully work in this context, see form submission functions below and admin.js for custom handling
			'html_after_fields' => '<button class="button cancel" type="reset">Cancel</button>',
			'submit_value'      => 'Add event',
			'return'            => '',
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
				'method' => 'post',
			),
			'fields'             => $fields,
			'html_before_fields' => '<div class="acf-spinner"></div>',
			'html_after_fields'  => '<button class="button cancel" type="reset">Cancel</button>',
			'ajax'               => true, // Note: ACF's AJAX doesn't fully work in this context, see form submission functions below and admin.js for custom handling
			'return'             => '',
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
	 * @throws Exception
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

		if($column_name === 'sort_date') {
			$value = get_post_meta($post_id, 'sort_date', true);
			if(!empty($value)) {
				$date = new DateTime($value);
				echo $date->format('Y-m-d');
			}
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

		if($column_name === 'external_link') {
			$field = get_field_object('external_link', $post_id);

			$field_key = $field['key'];
			$value = get_post_meta($post_id, 'external_link', true);
			$link = '';
			if($value) {
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.--><path d="M336 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l121.4 0L212.7 276.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L480 54.6 480 176c0 8.8 7.2 16 16 16s16-7.2 16-16l0-160c0-8.8-7.2-16-16-16L336 0zM64 32C28.7 32 0 60.7 0 96L0 448c0 35.3 28.7 64 64 64l352 0c35.3 0 64-28.7 64-64l0-144c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 144c0 17.7-14.3 32-32 32L64 480c-17.7 0-32-14.3-32-32L32 96c0-17.7 14.3-32 32-32l144 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L64 32z"/></svg>';
				$link = '<a class="external-link" href="' . esc_url($value['url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($value['title']) . $icon . '</a>';
			}

			// Display the field value wrapped in ID and field key identifiers so the JS can update it when inline edits are saved
			echo <<<HTML
			<span class="acf-field-value" data-field-key="$field_key" data-post-id="$post_id">$link</span>
			HTML;

			$form_id = 'acf-form-external-link-' . $post_id;
			$this->display_wrapped_acf_form($form_id, $post_id, ['external_link']);
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
	 * Save the common event date to post meta for query sorting and filtering
	 * @param $post_id
	 * @return void
	 */
	function save_common_event_date($post_id): void {
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if(!isset($_POST['acf'])) return;
		if(get_post_type($post_id) !== 'event') return;

		$current_date_type = get_post_meta($post_id, 'type', true);
		switch($current_date_type) {
			case 'single':
				$date = get_post_meta($post_id, 'single_date', true);
				break;
			case 'range':
				$date = get_post_meta($post_id, 'range_start_date', true);
				break;
			case 'multi':
				$date = get_post_meta($post_id, 'multi_dates_0_date', true);
				break;
			case 'multi_extended':
				$date = get_post_meta($post_id, 'multi_extended_0_date', true);
				break;
			default:
				$date = '';
				break;
		}

		update_post_meta($post_id, 'sort_date', $date);
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
		$type = get_field('type', $event_id);
		$dateComponent = null;
		$sortDate = get_post_meta($event_id, 'sort_date', true);
		// is the sort date in the past? If so, show the year. For upcoming dates, don't show the year
		$isUpcoming = $sortDate && $sortDate >= (new DateTime())->format('Ymd');
		switch($type) {
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
					'start_date' => $startDate,
					'end_date'   => $endDate,
					'colorTheme' => $colorTheme ?? ($isUpcoming ? 'secondary' : 'dark')
				]);
				break;
			default:
				break;
		}

		return $dateComponent;
	}

}
