<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Blocks {

	public function __construct() {
		add_action('init', [$this, 'register_blocks']);
		add_action('init', [$this, 'register_block_fields']);
	}

	function register_blocks() {
		$block_folders = scandir(dirname(__DIR__, 1) . '/src/blocks');
		foreach($block_folders as $folder) {
			if($folder === '.' || $folder === '..') continue;

			$block_path = dirname(__DIR__, 1) . '/src/blocks/' . $folder;
			if(is_dir($block_path)) {
				$block_json = $block_path . '/block.json';
				if(file_exists($block_json)) {
					register_block_type($block_json);
				}
			}
		}
	}

	/**
	 * Register ACF fields for custom blocks if there is a fields.php file containing them in the block folder
	 * @return void
	 */
	function register_block_fields(): void {
		$block_folders = scandir(dirname(__DIR__, 1) . '/src/blocks');
		foreach($block_folders as $block_name) {
			$file = dirname(__DIR__, 1) . '/src/blocks/' . $block_name . '/fields.php';

			if(file_exists($file) && function_exists('acf_add_local_field_group')) {
				require_once $file;
			}
		}
	}
}
