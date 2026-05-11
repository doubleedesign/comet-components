<?php
// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail early
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    return;
}

// Skip all this if this is not Comet Components
// Useful for local development where php.ini applies to multiple sites
if (!in_array(
    $_SERVER['HTTP_HOST'],
    ['comet-components.test', 'cometcomponents.io', 'storybook.comet-components.test', 'storybook.cometcomponents.io', 'localhost:7000'])
) {
    return;
}

use Doubleedesign\Comet\Core\{Assets, Config};

// Autoload dependencies using Composer
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';
// Enable dump() locally (assumes VarDumper is installed globally via Composer)
if (getenv('APPDATA') !== null) {
    require_once getenv('APPDATA') . '/Composer/vendor/autoload.php';
}

// Initialise and set global config
Config::init();
$globalBackground = Config::getInstance()->get_global_background();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php Assets::get_instance()->render_global_stylesheet_html(); ?>
	<?php Assets::get_instance()->render_global_script_html(); ?>
	<link href="https://use.typekit.net/svp5arr.css"/>
	<script src="https://kit.fontawesome.com/b92552f954.js" crossorigin="anonymous"></script>
	<style>
		#browser-test-content {
			container-name: body;
			container-type: inline-size;
			--global-background: <?php echo $globalBackground; ?>;
		}
	</style>
</head>
<body data-global-background="<?php echo $globalBackground; ?>">
<div id="browser-test-content">
