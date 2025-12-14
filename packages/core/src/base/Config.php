<?php
namespace Doubleedesign\Comet\Core;
use InvalidArgumentException, Exception;

/**
 * Singleton class to manage global configuration settings for Comet components.
 */
class Config {
    private static ?self $instance = null;
    private ThemeColor $global_background = ThemeColor::WHITE;
    private string $icon_prefix = 'fa-solid';
    private array $blade_component_paths = [];
    private array $component_defaults = [];
    private array $theme_colours = [];

    public static function init(): void {
        if (!defined('COMET_VERSION')) {
            define('COMET_VERSION', '0.3.0');
        }

        if (self::$instance === null) {
            self::$instance = new self();
        }
    }

    private function get_config() {
        return [
            'global_background'     => $this->global_background,
            'icon_prefix'           => $this->icon_prefix,
            'blade_component_paths' => $this->blade_component_paths,
            'component_defaults'    => $this->component_defaults,
            'theme_colours'         => $this->theme_colours
        ];
    }

    /**
     * Get the singleton instance of the Config class, instantiating it if it doesn't already exist.
     *
     * @return self
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::init();
        }

        return self::$instance;
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Comet Components core config: Cannot unserialize singleton");
    }

    public function get(string $key): mixed {
        if (!isset($this->get_config()[$key])) {
            throw new InvalidArgumentException("Comet Components core config: Invalid config key '$key'");
        }

        return $this->get_config()[$key] ?? null;
    }

    public function has(string $key): bool {
        return isset($this->config[$key]);
    }

    public function all(): array {
        return $this->config;
    }

    // Convenience methods for common operations
    public function get_global_background(): string {
        return $this->get('global_background')->value;
    }

    public function set_global_background(string|ThemeColor $color): void {
        if ($color instanceof ThemeColor) {
            $this->global_background = $color;
        }
        else {
            $color = ThemeColor::tryFrom($color) ?? ThemeColor::WHITE;
            $this->global_background = $color;
        }
    }

    public function get_theme_colours(): array {
        return $this->get('theme_colours');
    }

    public function set_theme_colours(array $colours): void {
        $this->theme_colours = $colours;
    }

    public function set_icon_prefix($prefix) {
        $this->icon_prefix = $prefix;
    }

    public function get_component_defaults(string $component): array {
        $defaults = $this->get('component_defaults');

        return $defaults[$component] ?? [];
    }

    public function set_component_defaults(string $component, array $settings): void {
        $defaults = $this->get('component_defaults', []);
        $defaults[$component] = $settings;
        $this->component_defaults = $defaults;
    }

    public function set_blade_component_paths(array $paths): void {
        $this->blade_component_paths = array_unique(array_merge($this->blade_component_paths, $paths));
    }
}
