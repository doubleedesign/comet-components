<?php
namespace Doubleedesign\CometCanvas\Classic;
use ReflectionClass, ReflectionException;

class ThemeEntrypoint {
    public function __construct() {
        add_filter('extra_theme_headers', [$this, 'register_namespace_header']);

        $this->maybe_instantiate_class(ThemeStyle::class);
        $this->maybe_instantiate_class(SharedContent::class);
        $this->maybe_instantiate_class(Fields::class);
        $this->maybe_instantiate_class(NavMenus::class);
        $this->maybe_instantiate_class(Frontend::class);

        if (is_admin()) {
            $this->maybe_instantiate_class(TinyMCEConfig::class);
            $this->maybe_instantiate_class(AdminUI::class);
            $this->maybe_instantiate_class(SiteHealth::class);
        }
    }

    public function register_namespace_header($headers) {
        $headers[] = 'PSR-4 Namespace';

        return $headers;
    }

    /**
     * Only instantiate a class from the theme entrypoint if the child theme doesn't have one with the same name.
     * This assumes child theme classes will extend parent theme classes and call parent constructors.
     *
     * @param  $class
     *
     * @return void
     */
    public function maybe_instantiate_class($class): void {
        try {
            $class_name = (new ReflectionClass($class))->getShortName();
            if (!$this->child_theme_has_class($class_name)) {
                new $class();
            }
        }
        catch (ReflectionException $e) {
            // Class doesn't exist, do nothing
        }
    }

    private function child_theme_has_class($class_name): bool {
        $child_namespace = $this->get_child_theme_namespace();

        return class_exists($child_namespace . '\\' . $class_name);
    }

    public function get_child_theme_namespace(): string {
        return 'Doubleedesign\\ZenchelKennels';
    }
}
