<?php
use Doubleedesign\Comet\Core\CometConfig;

// Autoload dependencies using Composer
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';
// Other dependencies
require_once __DIR__ . '/../common/mocks.php';

// Allow Storybook to access this server
$storybook = 'http://localhost:6006';
if(isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === $storybook) {
	header("Access-Control-Allow-Origin: " . $storybook);
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
}

$host = "http://$_SERVER[HTTP_HOST]";
$fileName = array_reverse(explode('/', $_SERVER['SCRIPT_NAME']))[0];
$supportingCss = [];
// For demo pages of component combinations:
if(str_starts_with($_SERVER['SCRIPT_NAME'], '/pages/')) {
	if($_SERVER['SCRIPT_NAME'] === '/pages/container-colours.php') {
		array_push($supportingCss, 'container.css');
	}
	if($_SERVER['SCRIPT_NAME'] === '/pages/group-colours.php') {
		array_push($supportingCss, 'group.css');
	}
	if($_SERVER['SCRIPT_NAME'] === '/pages/columns-colours.php') {
		array_push($supportingCss, 'container.css');
		array_push($supportingCss, 'columns.css');
		array_push($supportingCss, 'column.css');
	}
}
// For standalone components, load the stylesheet with the same name as the component
else if(str_starts_with($_SERVER['SCRIPT_NAME'], '/components/')) {
	$cssFileName = str_replace('.php', '.css', $fileName);
	array_push($supportingCss, $cssFileName);
}
// Specific cases where we need others
if($_SERVER['SCRIPT_NAME'] === '/components/button-group.php') {
	array_push($supportingCss, 'button.css');
}
if(str_contains($_SERVER['SCRIPT_NAME'], 'columns')) {
	array_push($supportingCss, 'column.css');
}
?>

<?php
$cssFiles = array_unique(array_merge([$cssFileName ?? ''], $supportingCss));
$cssFileLinkTags = join("\n\t", array_map(fn($cssFile) => "<link rel=\"stylesheet\" href=\"$host/assets/$cssFile\">", $cssFiles));
CometConfig::set_global_background('dark');
$globalBackground = CometConfig::get_global_background();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo $host; ?>/assets/global.css">
	<?php echo $cssFileLinkTags; ?>

	<script src="https://kit.fontawesome.com/dcb22fbf87.js" crossorigin="anonymous"></script>
</head>
<body data-global-background="<?php echo $globalBackground; ?>">
<div id="browser-test-content">
