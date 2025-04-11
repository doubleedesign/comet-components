<?php
use Doubleedesign\Comet\Core\{Assets,Config};

// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail early
if(!isset($_SERVER['HTTP_USER_AGENT'])) return;

// Allow Storybook to access this server
$storybook = 'http://localhost:6006';
if(isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === $storybook) {
	header("Access-Control-Allow-Origin: " . $storybook);
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

	// Handle preflight requests
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		http_response_code(200);
		exit;
	}
}

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
	<script src="https://kit.fontawesome.com/dcb22fbf87.js" crossorigin="anonymous"></script>
</head>
<body data-global-background="<?php echo $globalBackground; ?>">
<div id="browser-test-content">
