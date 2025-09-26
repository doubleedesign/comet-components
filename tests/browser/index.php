<?php
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';
use Doubleedesign\Comet\Core\Utils;

$path = dirname(__DIR__, 2) . '\packages\core\src\components';
$folders = array_diff(scandir($path), ['.', '..']);
$folders = array_filter($folders, function($name) {
	return !str_contains($name, '.') && !str_contains($name, '__');
});
$testComponentPaths = [];
foreach($folders as $folder) {
	$kebab = Utils::kebab_case($folder);
	$testComponentPaths[$folder] = "/packages/core/src/components/$folder/__tests__/$kebab.php";
}
?>

<ul>
	<?php foreach($testComponentPaths as $folder => $path): ?>
		<li>
			<a href="<?php echo $path; ?>"><?php echo $folder; ?></a>
		</li>
	<?php endforeach; ?>
</ul>
