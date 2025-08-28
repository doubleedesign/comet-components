<?php

namespace Doubleedesign\CometCanvas\Classic;

class Frontend {

    public function __construct() {
        add_filter('the_content', [$this, 'render_flexible_content'], 20);
    }

    public function render_flexible_content($content): string {
        $plugin_active = is_plugin_active('comet-plugin-acf/comet.php');
        if (!$plugin_active) {
            return $content;
        }

        return comet_acf_render_flexible_content(get_the_id());
    }
}
