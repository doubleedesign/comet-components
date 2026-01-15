<?php

use Doubleedesign\Comet\Core\{Config, IconLinks, Menu, SiteFooter};
use Doubleedesign\CometCanvas\NavMenus;

?>
</main>

<?php
$menuItems = NavMenus::get_simplified_nav_menu_items_by_location('footer');
$menuComponent = new Menu(['context' => 'site-footer'], $menuItems);
$socials = get_field('social_media_links', 'options');
$attributes = Config::getInstance()->get_component_defaults('site-footer') ?? [];

if ($socials) {
    $iconLinksComponent = new IconLinks([
        'aria-label' => 'Social media links',
        'context'    => 'site-footer',
    ], $socials);
    $footerComponent = new SiteFooter($attributes, [$iconLinksComponent, $menuComponent]);
}
else {
    $footerComponent = new SiteFooter($attributes, [$menuComponent]);
}

$footerComponent->render();

wp_footer(); ?>
</body>
</html>
