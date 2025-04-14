<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Admin {
	public function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_css']);
		add_action('init', [$this, 'register_page_template'], 15, 2);
	}

	function enqueue_admin_css() {
		$currentDir = plugin_dir_url(__FILE__);

		$css_path = $currentDir . 'assets/admin.css';
		wp_enqueue_style('comet-calendar-admin', $css_path, array(), COMET_CALENDAR_VERSION);
	}

	/**
	 * Default blocks for a new event
	 * @return void
	 */
	function register_page_template(): void {
		$template = [
			[
				'comet/container',
				[]
			],
		];
		$post_type_object = get_post_type_object('event');
		$post_type_object->template = $template;
		$post_type_object->template_lock = false;
	}

}
