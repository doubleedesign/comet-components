<?php
require_once __DIR__ . '/vendor/autoload.php';
use Doubleedesign\CometCanvas\{ThemeStyle, NavMenus, SiteHealth, WpAdmin, BlockEditorConfig};

add_action('plugins_loaded', function() {
    if (!class_exists('Doubleedesign\Comet\Core\Config')) {
        wp_die('<p>Comet Components Core Config class not found in Comet Canvas theme. Perhaps you need to install or update Composer dependencies.</p><p>If you are working locally with symlinked packages, you might want <code>$env:COMPOSER = "composer.local.json"; composer update</code>.</p>');
    }
}, 1);

new ThemeStyle();
new NavMenus();
new SiteHealth();
new WpAdmin();
new BlockEditorConfig();
