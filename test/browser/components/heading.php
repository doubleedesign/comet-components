<div id="browser-test-content">
	<?php
	use Doubleedesign\Comet\Core\Heading;

	try {
		$content = $_REQUEST['content'] ?? 'Heading';
		$attributes = [];
		if (isset($_REQUEST['classes'])) {
			$attributes['classes'] = explode(' ', $_REQUEST['classes']);
		}

		$component = new Heading($attributes, $content);
		$component->render();
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
</div>
