<?php

namespace Doubleedesign\CometCanvas\Classic;

use Doubleedesign\Comet\Core\Utils;

/**
 * This class sets up some core PHP stuff, notably allowing child themes to replace certain classes rather than extend them.
 * This means parent theme classes don't get instantiated at all,
 * meaning we can avoid things like the parent theme registering a menu only to have the child theme unregister it.
 * TODO: Is there a way to enforce child theme classes to implement the interfaces to ensure all required methods are present?
 */

final class CometCanvas {
    public function __construct() {
        add_filter('extra_theme_headers', [$this, 'register_namespace_header']);
        add_action('after_setup_theme', [$this, 'init']);
    }

    public function register_namespace_header($headers) {
        $headers[] = 'PSR-4 Namespace';

        return $headers;
    }

    public function init(): void {
        $this->instantiate_theme_class('NavMenus');
        $this->instantiate_theme_class('Frontend');
        $this->instantiate_theme_class('ThemeStyle');

        if (is_admin()) {
            $this->instantiate_theme_class('TinyMCEConfig');
            $this->instantiate_theme_class('AdminUI');
            $this->instantiate_theme_class('SiteHealth');
        }
    }

    private function instantiate_theme_class($class_name): void {
        $child_namespace = $this->get_child_theme_namespace();
        $child_class = $child_namespace . '\\' . $class_name;
        $class = class_exists($child_class) ? $child_class : __NAMESPACE__ . '\\' . $class_name;

        new $class();
    }

    private function get_child_theme_namespace(): ?string {
        $theme = wp_get_theme();
        // Comet Canvas Classic is being used as the parent; there is no child theme.
        if ($theme->get_stylesheet() === 'comet-canvas-classic') {
            return null;
        }

        return $theme->get('PSR-4 Namespace') ?? $this->get_assumed_namespace();
    }

    private function get_assumed_namespace(): string {
        $theme = wp_get_theme();

        $author = ucfirst(strtolower(Utils::pascal_case($theme->get('Author'))));
        $theme = Utils::pascal_case($theme->get_stylesheet());

        return $author . '\\' . $theme;
    }

    public static function get_theme_colours(): array {
        $child_namespace = (new self())->get_child_theme_namespace();
        $themeStyleClass = $child_namespace && class_exists($child_namespace . '\\ThemeStyle')
            ? $child_namespace . '\\ThemeStyle'
            : __NAMESPACE__ . '\\ThemeStyle';

        /** @var ThemeStyle $themeStyleClass */
        return $themeStyleClass::get_colours();
    }

    public static function get_simplified_nav_menu_items_by_location(string $location, array $args = []): array {
        $child_namespace = (new self())->get_child_theme_namespace();
        $navMenuClass = $child_namespace && class_exists($child_namespace . '\\NavMenus')
            ? $child_namespace . '\\NavMenus'
            : __NAMESPACE__ . '\\NavMenus';

        /** @var NavMenus $navMenuClass */
        return $navMenuClass::get_simplified_nav_menu_items_by_location($location, $args);
    }

    public static function get_global_background(): string {
        $child_namespace = (new self())->get_child_theme_namespace();
        $themeStyleClass = $child_namespace && class_exists($child_namespace . '\\ThemeStyle')
            ? $child_namespace . '\\ThemeStyle'
            : __NAMESPACE__ . '\\ThemeStyle';

        /** @var ThemeStyle $themeStyleClass */
        return $themeStyleClass::get_global_background();
    }

}
