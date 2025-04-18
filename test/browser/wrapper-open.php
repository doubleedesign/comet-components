<?php
// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail early
if(!isset($_SERVER['HTTP_USER_AGENT'])) return;

// Skip all this if this is not Comet Components
// Useful for local development where php.ini applies to multiple sites
if(!in_array($_SERVER['HTTP_HOST'], ['comet-components.test', 'cometcomponents.io'])) return;

$storybook = ['https://storybook.comet-components.test:6006', 'https://storybook.cometcomponents.io'];
if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $storybook)) {
	// Allow Storybook to access this server
	$storybook = $_SERVER['HTTP_ORIGIN'];
	header("Access-Control-Allow-Origin: " . $storybook);
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

	// Disable asset caching
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Pragma: no-cache");
	header("Expires: 0");

	// Handle preflight requests
	if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		http_response_code(200);
		exit;
	}
}

use Doubleedesign\Comet\Core\{Assets, Config};

// Autoload dependencies using Composer
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';

// Set global config
$globalBackground = Config::get_global_background();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php Assets::get_instance()->render_global_stylesheet_html(); ?>
	<?php Assets::get_instance()->render_global_script_html(); ?>
	<link href="https://use.typekit.net/daf8wql.css"/>
	<script src="https://kit.fontawesome.com/dcb22fbf87.js" crossorigin="anonymous"></script>
</head>
<body data-global-background="<?php echo $globalBackground; ?>">
<div id="browser-test-content">
