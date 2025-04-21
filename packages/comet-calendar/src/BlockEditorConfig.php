<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class BlockEditorConfig {
	function __construct() {
		add_filter('allowed_block_types_all', [$this, 'event_allowed_block_types'], 30, 2);
		add_action('init', [$this, 'register_page_template'], 20, 2);
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

}
