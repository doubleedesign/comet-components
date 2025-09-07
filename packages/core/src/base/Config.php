<?php
namespace Doubleedesign\Comet\Core;

/**
 * Singleton class to manage global configuration settings for Comet components.
 */
class Config {
    private static ?self $instance = null;

    // Set defaults
    private array $config = [
        'global_background'        => ThemeColor::WHITE,
        'icon_prefix'              => 'fa-solid',
        'blade_component_paths'    => [],
        'component_defaults'       => [],
        'theme_colours'            => [],
    ];

    /**
     * Get the singleton instance of the Config class, instantiating it if it doesn't already exist.
     *
     * @return self
     */
    public static function getInstance(): self {
        if (!defined('COMET_VERSION')) {
            define('COMET_VERSION', '0.2.0');
        }

        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Comet Components core config: Cannot unserialize singleton");
    }

    public function get(string $key): mixed {
        if (!isset($this->config[$key])) {
            throw new \InvalidArgumentException("Comet Components core config: Invalid config key '$key'");
        }

        return $this->config[$key] ?? null;
    }

    public function set(string $key, mixed $value): void {
        if (!isset($this->config[$key])) {
            throw new \InvalidArgumentException("Comet Components core config: Invalid config key '$key'");
        }

        $this->config[$key] = $value;
    }

    public function has(string $key): bool {
        return isset($this->config[$key]);
    }

    public function all(): array {
        return $this->config;
    }

    public function merge(array $config): void {
        $this->config = array_merge($this->config, $config);
    }

    // Convenience methods for common operations
    public function get_global_background(): string {
        return $this->get('global_background');
    }

    public function set_global_background(string $color): void {
        $this->set('global_background', $color);
    }

    public function get_theme_colours(): array {
        return $this->get('theme_colours');
    }

    public function get_component_defaults(string $component): array {
        $defaults = $this->get('component_defaults', []);

        return $defaults[$component] ?? [];
    }

    public function set_component_defaults(string $component, array $settings): void {
        $defaults = $this->get('component_defaults', []);
        $defaults[$component] = $settings;
        $this->set('component_defaults', $defaults);
    }
}
