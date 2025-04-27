<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class TemplateHandler {

	public function __construct() {
		add_filter('template_include', [$this, 'event_archive_template']);
	}

	function event_archive_template($template) {
		if(!is_post_type_archive('event')) return $template;

		$theme_template = locate_template('archive-event.php');
		if($theme_template) {
			return $theme_template;
		}
		else {
			$plugin_template = plugin_dir_path(__FILE__) . 'templates/archive-event.php';
			if(file_exists($plugin_template)) {
				return $plugin_template;
			}
		}

		// Return the default template if override conditions aren't met
		return $template;
	}

}
