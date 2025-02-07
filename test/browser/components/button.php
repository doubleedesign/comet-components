<div id="browser-test-content">
	<?php
	use Doubleedesign\Comet\Core\Button;

	try {
		$content = $_REQUEST['content'] ?? 'Button';
		$attributes = [
			'href' => $_REQUEST['href'] ?? '#',
			'classes' =>  isset($_REQUEST['classes']) ? explode(' ', $_REQUEST['classes']) : [],
			'colorTheme' => $_REQUEST['colorTheme'] ?? '',
		];

		$component = new Button($attributes, $content);
		$component->render();
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
</div>
