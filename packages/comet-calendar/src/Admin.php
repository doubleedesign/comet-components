<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Admin {
	public function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_css']);
	}

	function enqueue_admin_css(): void {
		$currentDir = plugin_dir_url(__FILE__);

		$css_path = $currentDir . 'assets/admin.css';
		wp_enqueue_style('comet-calendar-admin', $css_path, array(), COMET_CALENDAR_VERSION);
	}
}
