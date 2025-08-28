<?php
require_once __DIR__ . '/vendor/autoload.php';
use Doubleedesign\CometCanvas\Classic\{ThemeStyle, NavMenus, SiteHealth, WpAdmin, TinyMCEConfig, Frontend};

new ThemeStyle();
new NavMenus();
new Frontend();

if (is_admin()) {
    new TinyMCEConfig();
    new WpAdmin();
    new SiteHealth();
}
