<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Admin {
	public function __construct() {
		add_action('acf/init', [$this, 'register_options_page'], 5);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_css']);
		add_action('admin_bar_menu', [$this, 'add_archive_page_admin_bar_link'], 200);
		add_filter('acf/update_value', [$this, 'maybe_flush_permalinks'], 20, 3);
	}

	function register_options_page(): void {
		if(function_exists('acf_add_options_page')) {
			acf_add_options_page(array(
				'page_title'  => 'Calendar Settings',
				'menu_title'  => 'Calendar Settings',
				'menu_slug'   => 'calendar-settings',
				'parent_slug' => 'edit.php?post_type=event',
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'position'    => 2,
				'icon_url'    => 'dashicons-calendar-alt',
			));
		}
	}

	function enqueue_admin_css(): void {
		$currentDir = plugin_dir_url(__FILE__);

		$css_path = $currentDir . 'assets/admin.css';
		wp_enqueue_style('comet-calendar-admin', $css_path, array(), COMET_CALENDAR_VERSION);
	}

	function add_archive_page_admin_bar_link($admin_bar): void {
		if(!current_user_can('edit_posts')) return;

		if(is_admin()) {
			$screen = get_current_screen();
			if($screen->base === 'event_page_calendar-settings') {
				$admin_bar->add_menu([
					'id'     => 'event-archive',
					'title'  => 'View page',
					'href'   => get_post_type_archive_link('event'),
					'parent' => null,
					'group'  => false,
					'meta'   => '',
				]);
			}
		}
		else if(is_post_type_archive('event')) {
			$admin_bar->add_menu([
				'id'     => 'calendar-settings',
				'title'  => 'Edit page',
				'href'   => esc_url(admin_url('/edit.php?post_type=event&page=calendar-settings')),
				'parent' => null,
				'group'  => false,
				'meta'   => '',
			]);
		}
	}

	/**
	 * The Events archive page title, set in an ACF options page, is transformed and used as the URL slug in the CPT (see Events.php).
	 * This method ensures permalinks are refreshed when the title is changed.
	 * @param $value
	 * @param $post_id
	 * @param $field
	 * @return string
	 */
	function maybe_flush_permalinks($value, $post_id, $field): string {
		if($field['name'] === 'events_page_title') {
			flush_rewrite_rules();
		}

		return $value;
	}
}
