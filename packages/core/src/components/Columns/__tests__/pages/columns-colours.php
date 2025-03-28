<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = [
<<<TYCHO
<Container testId="example-1" withWrapper="false">
	<Columns>
		<Column>Column 1</Column>
		<Column>Column 2</Column>
	</Columns>
</Container>
TYCHO,

<<<TYCHO
<Container testId="example-2" withWrapper="false" backgroundColor="light">
	<Columns>
		<Column>Column 1</Column>
		<Column>Column 2</Column>
	</Columns>
</Container>
TYCHO

];

foreach($page as $template) {
	try {
		$component = TychoService::parse($template);
		$component->render();
	}
	catch(Exception $e) {
		echo $e->getMessage();
	}
}
