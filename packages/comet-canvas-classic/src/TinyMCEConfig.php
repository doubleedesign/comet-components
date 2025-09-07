<?php

namespace Doubleedesign\CometCanvas\Classic;

use Doubleedesign\Comet\Core\Config;

class TinyMCEConfig {

    public function __construct() {
        add_filter('tiny_mce_before_init', [$this, 'add_editor_styles'], 10, 1);
        add_filter('tiny_mce_before_init', [$this, 'add_theme_colours'], 10, 1);

        // Note: The Comet for ACF plugin also adds its own customisations to the buttons available in ACF WYSIWYG fields.
    }

    /**
     * Load editor styles in ACF WYSIWYG fields
     * Ref: https://pagegwood.com/web-development/custom-editor-stylesheets-advanced-custom-fields-wysiwyg/
     *
     * @param  $mce_init
     *
     * @wp-hook
     *
     * @return array
     */
    public function add_editor_styles($mce_init): array {
        $content_css = '/editor-styles.css';
        if (file_exists(get_stylesheet_directory() . $content_css)) {
            $content_css = get_stylesheet_directory_uri() . $content_css . '?v=' . time(); // it caches hard, use this to force a refresh
        }
        else if (file_exists(get_template_directory() . $content_css)) {
            $content_css = get_template_directory_uri() . $content_css . '?v=' . time();
        }
        else {
            $content_css = false;
        }

        if (isset($mce_init['content_css']) && $content_css) {
            $content_css_new = $mce_init['content_css'] . ',' . $content_css;
            $mce_init['content_css'] = $content_css_new;
        }

        return $mce_init;
    }

    /**
     * Add predefined colours
     *
     * @wp-hook
     *
     * @param  $settings
     *
     * @return array
     */
    public function add_theme_colours($settings): array {
        $colours = Config::getInstance()->get_theme_colours();

        if (!empty($colours)) {
            $map = array();
            foreach ($colours as $value => $label) {
                $map[] = '"' . $value . '","' . $label . '"';
            }

            $settings['textcolor_map'] = '[' . implode(',', $map) . ']';
        }

        return $settings;
    }
}
