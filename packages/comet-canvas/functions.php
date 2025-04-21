<?php
require_once __DIR__ . '/vendor/autoload.php';
use Doubleedesign\CometCanvas\{ThemeStyle, NavMenus, SiteHealth, WpAdmin, BlockEditorConfig};

new ThemeStyle();
new NavMenus();
new SiteHealth();
new WpAdmin();
new BlockEditorConfig();
