<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Container testId="example-1" withWrapper="false">
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	<Container testId="example-2" withWrapper="false" backgroundColor="light">
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
</TychoTemplate>
TYCHO;

try {
	$components = TychoService::parse($page);
	foreach($components as $component) {
		$component->render();
	}
}
catch(Exception $e) {
	echo $e->getMessage();
}
