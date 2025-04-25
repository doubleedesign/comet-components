<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Admin {
	public function __construct() {
		add_action('acf/init', [$this, 'register_options_page'], 5);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_css']);
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
}
