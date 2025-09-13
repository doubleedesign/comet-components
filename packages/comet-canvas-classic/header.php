<?php
use Doubleedesign\Comet\Core\{Config, SiteHeader, Group, Menu};

$globalBackground = Config::getInstance()->get('global_background');
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

<body <?php body_class('frontend'); ?> data-global-background="<?php echo $globalBackground; ?>">
<?php wp_body_open(); ?>

<?php
$menuItems = apply_filters('comet_get_simplified_menu_items_by_location', 'primary');
$menuComponent = new Menu(['context' => 'site-header'], $menuItems);
$logoId = get_option('options_logo');
$logoUrl = wp_get_attachment_image_url($logoId, 'full');
$headerComponent = new SiteHeader(
    [
        'logoUrl'         => $logoUrl,
        'size'            => 'wide',
        'breakpoint'      => '860px',
        'responsiveStyle' => 'default',
        'submenuIcon'     => 'fa-caret-down'
    ],
    [new Group(['context' => 'responsive'], [$menuComponent])]
);
$headerComponent->render();
?>

<!--<a class="skip-link screen-reader-text" href="#primary">-->
<?php // esc_html_e('Skip to content', 'comet');?><!--</a>-->

<main class="site-content layout-block page-section">
