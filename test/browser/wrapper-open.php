<?php
use Doubleedesign\Comet\Core\CometConfig;

// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail early
if(!isset($_SERVER['HTTP_USER_AGENT'])) return;

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
$assetPath = "$host/assets";
// If the project is running as a Herd site, adjust the asset path
if(isset($_SERVER['HERD_HOME']) && $_SERVER['HTTP_HOST'] === 'comet-components.test') {
	$assetPath = "$host/test/browser/assets";
}
// Likewise if running from PhpStorm's built-in web server (what opens when you click an "open in browser" button)
if(str_contains($_SERVER['SERVER_NAME'], 'PhpStorm')) {
	$assetPath = "$host/test/browser/assets";
}

$path = $_SERVER['REQUEST_URI'];
$fileName = array_reverse(explode('/', $path))[0];
$supportingCss = [];
$scripts = [];
// For demo pages of component combinations:
if(str_starts_with($path, '/pages/') || str_starts_with($path, '/test/browser/pages/')) {
	if(str_contains($path, '/pages/container-colours.php')) {
		array_push($supportingCss, 'container.css');
	}
	if(str_contains($path, '/pages/group-colours.php')) {
		array_push($supportingCss, 'group.css');
	}
	if(str_contains($path, '/pages/columns-colours.php')) {
		array_push($supportingCss, 'container.css');
		array_push($supportingCss, 'columns.css');
		array_push($supportingCss, 'column.css');
		array_push($supportingCss, 'group.css');
		array_push($supportingCss, 'separator.css');
	}
}
// For standalone components, load the stylesheet with the same name as the component
else if(str_starts_with($path, '/components/')) {
	$cssFileName = str_replace('.php', '.css', $fileName);
	array_push($supportingCss, $cssFileName);
}
// Specific cases where we need others
if($path === '/components/button-group.php') {
	array_push($supportingCss, 'button.css');
}
if(str_contains($path, '/components/columns.php')) {
	array_push($supportingCss, 'columns.css');
	array_push($supportingCss, 'column.css');
}
// This is Responsive Panels, not sure why the symlink is only panels, not urgent to fix so haven't
if(str_contains($path, '/components/panels.php')) {
	array_push($supportingCss, 'responsive-panels.css');
	array_push($supportingCss, 'accordion.css');
	array_push($supportingCss, 'tabs.css');
}
?>

<?php
$cssFiles = isset($cssFileName) ? array_unique($cssFileName, $supportingCss) : $supportingCss;
$cssFileLinkTags = join("\n\t", array_map(fn($cssFile) => "<link rel=\"stylesheet\" href=\"$assetPath/$cssFile\">", $cssFiles));
$globalBackground = CometConfig::get_global_background();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo $assetPath; ?>/global.css">
	<?php echo $cssFileLinkTags; ?>
	<script type="module"
			src="http://comet-components.test/packages/core/dist/dist.js"
			data-base-path="/packages/core/"
	></script>
	<script src="https://kit.fontawesome.com/dcb22fbf87.js" crossorigin="anonymous"></script>
</head>
<body data-global-background="<?php echo $globalBackground; ?>">
<div id="browser-test-content">
