<?php
require_once __DIR__ . '/vendor/autoload.php';
use Doubleedesign\CometCanvas\Classic\{ThemeStyle, NavMenus, SiteHealth, WpAdmin, TinyMCEConfig};

new ThemeStyle();
new NavMenus();

if (is_admin()) {
    new TinyMCEConfig();
    new WpAdmin();
    new SiteHealth();
}
