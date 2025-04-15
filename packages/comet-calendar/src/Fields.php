<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Fields {

	public function __construct() {
		add_action('acf/update_field_group', [$this, 'save_acf_fields_to_this_plugin'], 20, 1);
		add_filter('acf/settings/save_json', [$this, 'override_acf_json_save_location'], 300);
		add_filter('acf/settings/load_json', [$this, 'load_acf_fields_from_this_plugin']);
	}

	/**
	 * Shared utility function to conditionally change the ACF JSON save location
	 * - to be used for field groups relevant to CPTs, taxonomies, etc. introduced by this plugin
	 * - when called, must be wrapped in a relevant conditional to identify the group to save to the plugin
	 * @return void
	 */
	public static function override_acf_json_save_location(): void {
		// remove this filter so it will not affect other groups
		remove_filter('acf/settings/save_json', 'override_acf_json_save_location', 500);

		add_filter('acf/settings/save_json', function($path) {
			// remove this filter so it will not affect other groups
			remove_filter('acf/settings/save_json', 'override_acf_json_save_location', 500);

			// override save path in this case
			return COMET_CALENDAR_PLUGIN_PATH . 'src/acf-json';
		}, 9999);
	}

	/**
	 * Override the save location for ACF JSON files for field groups that are stored in this plugin
	 * @param $group
	 *
	 * @return void
	 */
	function save_acf_fields_to_this_plugin($group): void {
		$groups = array_diff(scandir(COMET_CALENDAR_PLUGIN_PATH . '/src/acf-json'), ['..', '.']);

		if(in_array($group['key'] . '.json', array_values($groups))) {
			self::override_acf_json_save_location();
		}
	}


	/**
	 * Enable loading JSON files of ACF fields from this plugin
	 * @param $paths
	 *
	 * @return array
	 */
	function load_acf_fields_from_this_plugin($paths): array {
		$paths[] = COMET_CALENDAR_PLUGIN_PATH . '/src/acf-json';

		return $paths;
	}
}
