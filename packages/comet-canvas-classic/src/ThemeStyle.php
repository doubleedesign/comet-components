<?php
namespace Doubleedesign\CometCanvas\Classic;
use Doubleedesign\Comet\Core\{Config};

class ThemeStyle {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_theme_stylesheets'], 20);
        // Set defaults for components as per Config class in the core package
        add_action('init', [$this, 'set_colours'], 10);
        add_action('init', [$this, 'set_global_background'], 10);
        add_action('init', [$this, 'set_icon_prefix'], 10);
        add_action('init', [$this, 'set_component_defaults'], 10);

        add_theme_support('post-thumbnails', ['post']);
    }

    public function enqueue_theme_stylesheets(): void {
        $parent = get_template_directory() . '/style.css';
        $child = get_stylesheet_directory() . '/style.css';
        $deps = [];

        if (file_exists($parent)) {
            $parent = get_template_directory_uri() . '/style.css';
            wp_enqueue_style('comet-canvas', $parent, $deps, COMET_VERSION);
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

    public function set_colours(): void {
        $defaults = array(
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

        $colours = apply_filters('comet_canvas_theme_colours', $defaults);

        if (class_exists('Doubleedesign\Comet\Core\Config')) {
            Config::getInstance()->set_theme_colours($colours);
        }
    }

    public function set_global_background(): void {
        $color = apply_filters('comet_canvas_global_background', 'white');

        if (class_exists('Doubleedesign\Comet\Core\Config') && $color !== null) {
            Config::getInstance()->set_global_background($color);
        }
    }

    public function set_icon_prefix(): void {
        $prefix = apply_filters('comet_canvas_default_icon_prefix', 'fa-solid');

        if (class_exists('Doubleedesign\Comet\Core\Config')) {
            Config::getInstance()->set_icon_prefix($prefix);
        }
    }

    public function set_component_defaults(): void {
        $defaults = apply_filters('comet_canvas_component_defaults', []);

        if (class_exists('Doubleedesign\Comet\Core\Config')) {
            foreach ($defaults as $componentName => $settings) {
                $defaults = Config::getInstance()->get_component_defaults($componentName);
                $defaults[$componentName] = array_merge($existing[$componentName] ?? [], $settings);
                Config::getInstance()->set_component_defaults($componentName, $defaults[$componentName]);
            }
        }
    }
}
