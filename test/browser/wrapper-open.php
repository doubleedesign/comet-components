<?php
// Autoload dependencies using Composer
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';
// Other dependencies
require_once __DIR__ . '/../common/mocks.php';

// Allow Storybook to access this server
$storybook = 'http://localhost:6006';
if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === $storybook) {
	header("Access-Control-Allow-Origin: " . $storybook);
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
}

$host = "http://$_SERVER[HTTP_HOST]";
$fileName = array_reverse(explode('/', $_SERVER['SCRIPT_NAME']))[0];
$cssFileName = str_replace('.php', '.css', $fileName);
$supportingCss = [];
if($_SERVER['SCRIPT_NAME'] === '/components/button-group.php') {
	array_push($supportingCss, str_replace('button-group', 'button', $cssFileName));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo "$host/assets/global.css" ?>">
	<link rel="stylesheet" href="<?php echo "$host/assets/$cssFileName"; ?>">
	<script src="https://kit.fontawesome.com/dcb22fbf87.js" crossorigin="anonymous"></script>
	<?php
	foreach ($supportingCss as $supportingCssFile) { ?>
		<link rel="stylesheet" href="<?php echo "$host/assets/$supportingCssFile"; ?>">
	<?php } ?>
</head>
<body>
<div id="browser-test-content">
