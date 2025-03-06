<?php
use Doubleedesign\Comet\Core\{SiteHeader, Menu};
use Doubleedesign\CometCanvas\NavMenus;

?>
<!doctype html>
<html <?php language_attributes(); ?> lang="en">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<title><?php wp_title(); ?></title>
	<?php wp_head(); ?>
</head>

<body <?php body_class('frontend'); ?>>
<?php wp_body_open(); ?>

<?php
$menuItems = NavMenus::get_simplified_nav_menu_items_by_location('primary');
$menuComponent = new Menu(['context' => 'site-header'], $menuItems);
$logoId = get_option('options_logo');
$logoUrl = wp_get_attachment_image_url($logoId, 'full');
$headerComponent = new SiteHeader(['logoUrl' => $logoUrl, 'backgroundColor' => 'white'], [$menuComponent]);
$headerComponent->render();
?>

<!--<a class="skip-link screen-reader-text" href="#primary">-->
<?php //esc_html_e('Skip to content', 'comet'); ?><!--</a>-->

