<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class BlockEditorConfig {

    public function __construct() {
        add_filter('use_block_editor_for_post_type', [$this, 'use_block_editor_for_post_type'], 10, 2);
    }

    public function use_block_editor_for_post_type($current_status, $post_type): bool {
        if ($post_type === 'event') {
            return false;
        }

        return $current_status;
    }
}
