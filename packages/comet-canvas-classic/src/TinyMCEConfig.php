<?php

namespace Doubleedesign\CometCanvas\Classic;

class TinyMCEConfig {

    public function __construct() {
        add_filter('tiny_mce_before_init', [$this, 'add_editor_styles'], 10, 1);
        add_filter('tiny_mce_before_init', [$this, 'add_theme_colours'], 10, 1);
        add_filter('tiny_mce_before_init', [$this, 'populate_styleselect'], 10, 1);
        add_filter('mce_buttons', [$this, 'customise_row_1'], 5, 1);
        add_filter('mce_buttons_2', [$this, 'add_styleselect'], 10, 1);
        add_action('admin_init', [$this, 'add_select2_custom_css']);
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
        $colours = ThemeStyle::get_colours();

        if (!empty($colours)) {
            $map = array();
            foreach ($colours as $value => $label) {
                $map[] = '"' . $value . '","' . $label . '"';
            }

            $settings['textcolor_map'] = '[' . implode(',', $map) . ']';
        }

        return $settings;
    }

    /**
     * Customise first row of buttons
     *
     * @param  $buttons
     *
     * @return array
     */
    public function customise_row_1($buttons): array {
        $buttons = array_diff($buttons, ['wp_more', 'fullscreen']);

        return $buttons;
    }

    /**
     * Add custom formats menu
     *
     * @param  $buttons
     *
     * @return array
     */
    public function add_styleselect($buttons): array {
        array_unshift($buttons, 'styleselect');

        return $buttons;
    }

    /**
     * Populate custom formats menu
     * Notes: - 'selector' for block-level element that format is applied to; 'inline' to add wrapping tag e.g.'span'
     *        - Using 'attributes' to apply the classes instead of 'class' ensures previous classes are replaced rather than added to
     *        - 'styles' are inline styles that are applied to the items in the menu, not the output; options are pretty limited but enough to add things like colours
     *          (further styling customisation to the menu may be done in the admin stylesheet)
     *
     * @param  $settings
     *
     * @return array
     */
    public function populate_styleselect($settings): array {
        $style_formats = array(
            array(
                'title'   => 'Lead paragraph',
                'block'   => 'p',
                'classes' => 'lead'
            ),
            array(
                'title'      => 'Button (primary)',
                'selector'   => 'a',
                'attributes' => array(
                    'class' => 'btn btn--primary btn--icon'
                ),
                'styles' => array(
                    'color'         => 'white',
                    'background'    => '5F8575',
                    'fontWeight'    => 'bold'
                )
            ),
            array(
                'title'      => 'Button (secondary)',
                'selector'   => 'a',
                'attributes' => array(
                    'class' => 'btn btn--secondary btn--icon'
                ),
                'styles' => array(
                    'color'         => 'white',
                    'background'    => '374B43',
                    'fontWeight'    => 'bold'
                )
            ),
            array(
                'title'      => 'Button (accent)',
                'selector'   => 'a',
                'attributes' => array(
                    'class' => 'btn btn--accent btn--icon'
                ),
                'styles' => array(
                    'color'         => 'white',
                    'background'    => 'CC6600',
                    'fontWeight'    => 'bold'
                )
            )
        );

        $settings['style_formats'] = json_encode($style_formats);

        unset($settings['preview_styles']);

        return $settings;
    }

    public function add_select2_custom_css(): void {
        wp_enqueue_style('select2-admin-custom', get_template_directory_uri() . '/src/assets/select2.css', [], '0.0.3');
    }

}
