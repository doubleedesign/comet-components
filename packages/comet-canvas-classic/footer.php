<?php
use Doubleedesign\Comet\Core\{SiteFooter, Menu, IconLinks};

?>
</main>

<?php
$menuItems = apply_filters('comet_get_simplified_menu_items_by_location', 'footer');
$menuComponent = new Menu(['context' => 'site-footer'], $menuItems);
$socials = get_field('social_media_links', 'options');
if ($socials) {
    $iconLinksComponent = new IconLinks([
        'aria-label' => 'Social media links',
        'context'    => 'site-footer',
    ], $socials);
    $footerComponent = new SiteFooter(['backgroundColor' => 'dark'], [$iconLinksComponent, $menuComponent]);
}
else {
    $footerComponent = new SiteFooter(['backgroundColor' => 'dark'], [$menuComponent]);
}

$footerComponent->render();

wp_footer(); ?>
</body>
</html>
