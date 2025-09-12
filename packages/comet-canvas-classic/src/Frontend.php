<?php

namespace Doubleedesign\CometCanvas\Classic;

class Frontend {

    public function __construct() {
        add_filter('the_content', [$this, 'render_flexible_content'], 20);
        add_action('comet_canvas_blog_top_content', [$this, 'render_blog_top_content']);
    }

    /**
     * Replace the_content with the output of flexible content rendering
     * for the current page/post.
     *
     * @param  $content
     *
     * @return string
     */
    public function render_flexible_content($content): string {
        $plugin_active = is_plugin_active('comet-plugin-acf/comet.php');
        if (!$plugin_active) {
            return $content;
        }

        return comet_acf_render_flexible_content(get_the_id());
    }

    public function render_blog_top_content(): void {
        $plugin_active = is_plugin_active('comet-plugin-acf/comet.php');
        if (!$plugin_active || !get_option('page_for_posts')) {
            return;
        }

        echo comet_acf_render_flexible_content(get_option('page_for_posts'));
        wp_reset_postdata();
    }

    public static function get_contact_details_fields(): array {
        $expected = array_reduce(['address', 'suburb', 'state', 'postcode', 'phone', 'email'], function($carry, $field) {
            $value = get_option("options_contact_details_$field");
            if ($value) {
                $carry[$field] = $value;
            }

            return $carry;
        }, []);

        return apply_filters('comet_canvas_classic_contact_details_fields', $expected);
    }
}
