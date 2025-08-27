<?php
namespace Doubleedesign\CometCanvas\Classic;
use Doubleedesign\Comet\Core\Config;

class ThemeStyle {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_theme_stylesheets'], 20);
        // Set defaults for components as per Config class in the core package
        add_action('init', [$this, 'set_global_background'], 10);
        add_action('init', [$this, 'set_icon_prefix'], 10);
    }

    public static function get_colours(): array {
        $default_colours = array(
            '000000'       => 'Black',
            'FFFFFF'       => 'White',
            '845ec2'       => 'Primary',
            '00c9a7'       => 'Secondary',
            'ba3caf'       => 'Accent',
            '00d2fc'       => 'Info',
            'f9c971'       => 'Warning',
            '00c9a6'       => 'Success',
            'd23e3e'       => 'Error',
            '4b4453'       => 'Dark',
            'F0F0F2'       => 'Light'
        );

        // Use this to set colours from child themes
        return apply_filters('comet_canvas_colour_palette', $default_colours);
    }

    public function enqueue_theme_stylesheets(): void {
        $parent = get_template_directory() . '/style.css';
        $child = get_stylesheet_directory() . '/style.css';
        $deps = [];

        if (file_exists($parent)) {
            $parent = get_template_directory_uri() . '/style.css';
            wp_enqueue_style('comet-canvas', $parent, $deps, '0.0.3'); // TODO: Get version dynamically
        }

        if (file_exists($child)) {
            $child = get_stylesheet_directory_uri() . '/style.css';
            $theme = wp_get_theme();
            $slug = sanitize_title($theme->get('Name'));

            if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') {
                wp_enqueue_style($slug, $child, $deps, time()); // bust cache locally
            }
            else {
                wp_enqueue_style($slug, $child, $deps, $theme->get('Version'));
            }
        }
    }

    public function set_global_background(): void {
        $color = apply_filters('comet_canvas_global_background', 'white');
        Config::set_global_background($color);
    }

    public function set_icon_prefix(): void {
        $prefix = apply_filters('comet_canvas_default_icon_prefix', 'fa-solid');
        Config::set_icon_prefix($prefix);
    }
}
