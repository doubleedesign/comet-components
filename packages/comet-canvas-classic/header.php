<?php
use Doubleedesign\Comet\Core\{Config, SiteHeader, Group, Menu};

$globalBackground = Config::getInstance()->get_global_background();
?>
<!doctype html>
<html <?php language_attributes(); ?> lang="en">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<title><?php wp_title(); ?></title>
	<script type="text/javascript">
		// Hide body and make it visible again after a short delay to avoid flash of unstyled content
		// and to avoid showing content before animations kick in (if they are present)
		// This is done here in case anything goes wrong with other JS files
		// - so the content will always become visible even if animation JS breaks for example
		document.addEventListener('DOMContentLoaded', function() {
			document.body.style.opacity = "0";
			setTimeout(function () {
				document.body.style.opacity = "1";
			}, 100);
		});
	</script>
	<?php wp_head(); ?>
</head>

<?php // Opacity 0 on initial load so we don't get a flash of content before the animations, if present?>
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

<!--<a class="skip-link screen-reader-text" href="#content">-->
<?php // esc_html_e('Skip to content', 'comet');?><!--</a>-->
<?php /* TODO: Is layout-block needed here? */ ?>
<main id="content" class="site-content page-section">
