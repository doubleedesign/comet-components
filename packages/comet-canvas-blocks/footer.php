<?php

use Doubleedesign\Comet\Core\{Config, PreprocessedHTML, Menu, SiteFooter};
use Doubleedesign\CometCanvas\NavMenus;

?>
</main>

<?php
$menuItems = NavMenus::get_simplified_nav_menu_items_by_location('footer');
$menuComponent = new Menu([], $menuItems);
$attributes = Config::getInstance()->get_component_defaults('site-footer') ?? [];
ob_start();
get_template_part('template-parts/social-links', null, ['context' => 'site-footer']);
$socials = new PreprocessedHTML([], ob_get_clean());


$footerComponent = new SiteFooter($attributes, [$menuComponent, $socials]);
$footerComponent->render();

wp_footer(); ?>
</body>
</html>
