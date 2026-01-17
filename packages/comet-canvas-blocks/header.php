<?php
use Doubleedesign\Comet\Core\{Config, Group, Menu, PreprocessedHTML, SiteHeader};
use Doubleedesign\CometCanvas\NavMenus;

$globalBackground = Config::getInstance()->get('global_background')->value;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script type="text/javascript">
		// Ensure body is made visible after a short delay to avoid flash of unstyled content (inline style sets it to 0 initially)
		// and to avoid showing content before animations kick in (if they are present)
		// This is done here in case anything goes wrong with other JS files
		// - so the content will always become visible even if animation JS breaks for example
		document.addEventListener('DOMContentLoaded', function () {
			setTimeout(function () {
				document.body.style.opacity = "1";
			}, 100);
		});
	</script>
	<?php wp_head(); ?>
</head>

<?php // Opacity 0 on initial load so we don't get a flash of content before the animations, if present?>
<body <?php body_class('frontend'); ?> data-global-background="<?php echo $globalBackground; ?>" style="opacity:0">
<?php wp_body_open(); ?>

<?php
$menuItems = NavMenus::get_simplified_nav_menu_items_by_location('primary');
$menuComponent = new Menu(['context' => 'site-header'], $menuItems);
$logoId = get_option('options_logo');
$logoUrl = wp_get_attachment_image_url($logoId, 'full');

$showContactDetails = apply_filters('comet_canvas_show_contact_details_in_header', false);
if($showContactDetails) {
	ob_start();
	get_template_part('template-parts/contact-details');
	$contactBlockHtml = ob_get_clean();
	$contactBlock = new PreprocessedHTML([], $contactBlockHtml);
}

if($showContactDetails) {
	$headerComponent = new SiteHeader(
		[
			'logoUrl'         => $logoUrl,
			'size'            => 'contained',
			'breakpoint'      => '1024px',
			'responsiveStyle' => 'overlay',
			'icon'            => 'fa-bars',
			'submenuIcon'     => 'fa-plus'
		],
		[
			new Group(['context' => 'site-header', 'shortName' => 'contact'], [$contactBlock]),
			new Group(['context' => 'responsive'], [$contactBlock, $menuComponent])
		]
	);
}
else {
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
}

$headerComponent->render();
?>

<!--<a class="skip-link screen-reader-text" href="#primary">-->
<?php // esc_html_e('Skip to content', 'comet');?><!--</a>-->
<main class="site-content">
