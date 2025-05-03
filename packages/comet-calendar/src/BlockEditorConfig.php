<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class BlockEditorConfig {
	private static array $theme_json;

	function __construct() {
		$this->load_merged_theme_json();

		add_filter('allowed_block_types_all', [$this, 'event_allowed_block_types'], 30, 2);
		add_action('init', [$this, 'register_page_template'], 20, 2);

		if(is_admin()) {
			add_action('init', [$this, 'register_component_stylesheets'], 20, 2);
		}
	}

	protected function load_merged_theme_json(): void {
		$plugin_theme_json_path = WP_PLUGIN_DIR . '/comet-plugin/src/theme.json';
		$plugin_theme_json_data = json_decode(file_get_contents($plugin_theme_json_path), true);
		$final_theme_json = $plugin_theme_json_data;

		$theme_json_file = get_stylesheet_directory() . '/theme.json';
		if(file_exists($theme_json_file)) {
			$theme_json_data = json_decode(file_get_contents($theme_json_file), true);
			$final_theme_json = self::array_merge_deep($plugin_theme_json_data, $theme_json_data);
		}

		self::$theme_json = $final_theme_json;
	}

	/**
	 * Limit block types allowed on events
	 * @param $allowed_blocks
	 * @param \WP_Block_Editor_Context|null $context
	 * @return array
	 */
	function event_allowed_block_types($allowed_blocks, \WP_Block_Editor_Context $context = null): array {
		if($context === null) return $allowed_blocks;
		$post = $context->post;
		if(!$post) return $allowed_blocks;

		// If all blocks are allowed ($allowed_blocks = true), we need to get all registered blocks first
		if($allowed_blocks === true) {
			$all_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
			$allowed_blocks = array_keys($all_blocks);
		}

		if($post->post_type === 'event') {
			$filtered = array_filter($allowed_blocks, function($block) {
				return !in_array($block, ['comet/container', 'comet/banner', 'comet/panels', 'core/columns', 'comet/steps', 'core/freeform']);
			}, ARRAY_FILTER_USE_BOTH);

			return array_values($filtered);
		}

		return $allowed_blocks;
	}

	/**
	 * Default blocks for a new event
	 * @return void
	 */
	function register_page_template(): void {
		$template = [
			[
				'core/group', [],
				[
					[
						'core/paragraph',
						[
							'placeholder' => 'Event description',
							'content'     => '',
						],
					],
				],
			],
		];
		$post_type_object = get_post_type_object('event');
		$post_type_object->template = $template;
		$post_type_object->template_lock = false;
	}


	/**
	 * Register CSS that is loaded from the Comet Components plugin
	 * This can then be used in block.json by the handle specified here, solving relative path and symlink incompatibilities
	 */
	function register_component_stylesheets(): void {
		wp_register_style('comet-event-list-style', plugins_url('/comet-plugin/vendor/doubleedesign/comet-components-core/src/components/EventList/event-list.css'));
		wp_register_style('comet-event-card-style', plugins_url('/comet-plugin/vendor/doubleedesign/comet-components-core/src/components/EventCard/event-card.css'));
		wp_register_style('comet-date-block-style', plugins_url('/comet-plugin/vendor/doubleedesign/comet-components-core/src/components/DateBlock/date-block.css'));
		wp_register_style('comet-date-range-block-style', plugins_url('/comet-plugin/vendor/doubleedesign/comet-components-core/src/components/DateRangeBlock/date-range-block.css'));
	}

	public static function hex_to_theme_color_name($hex): ?string {
		$theme = self::$theme_json['settings']['color']['palette'];

		return array_reduce($theme, function($carry, $item) use ($hex) {
			if(isset($item['slug']) && isset($item['color'])) {
				return strtoupper($item['color']) === strtoupper($hex) ? $item['slug'] : $carry;
			}
			return $carry;
		}, null);
	}

	/**
	 * Deep merging of a multidimensional array where one is a partial variation of the other
	 * @param array $original
	 * @param array $partial
	 * @return array
	 */
	public static function array_merge_deep(array $original, array $partial): array {
		$result = $original;

		foreach($partial as $key => $value) {
			// If both are arrays but the original is indexed (numeric keys),
			// or if the value isn't an array, replace entirely
			if(!isset($original[$key]) || !is_array($value) || (is_array($original[$key]) && array_is_list($original[$key]))) {
				$result[$key] = $value;
			}
			// If both are associative arrays, merge recursively
			else if(is_array($original[$key])) {
				$result[$key] = self::array_merge_deep($original[$key], $value);
			}
		}

		return $result;
	}
}
