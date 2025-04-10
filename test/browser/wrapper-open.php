<?php
use Doubleedesign\Comet\Core\CometConfig;

// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail early
if(!isset($_SERVER['HTTP_USER_AGENT'])) return;

// Autoload dependencies using Composer
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';

// Allow Storybook to access this server
$storybook = 'http://localhost:6006';
if(isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === $storybook) {
	header("Access-Control-Allow-Origin: " . $storybook);
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
}

// Set global config
$globalBackground = CometConfig::get_global_background();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://kit.fontawesome.com/dcb22fbf87.js" crossorigin="anonymous"></script>
</head>
<body data-global-background="<?php echo $globalBackground; ?>">
<div id="browser-test-content">
