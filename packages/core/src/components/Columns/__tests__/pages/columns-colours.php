<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Container testId="example-1" withWrapper="false">
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
			<Column backgroundColor="light">Column 3</Column>
			<Column>Column 4</Column>
		</Columns>
	</Container>
	<Container testId="example-2" withWrapper="false" backgroundColor="primary">
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
		<Columns backgroundColor="light">
			<Column backgroundColor="primary">Column 1</Column>
			<Column backgroundColor="primary">Column 2</Column>
		</Columns>
		<Columns backgroundColor="light">
			<Column backgroundColor="light">Column 1</Column>
			<Column backgroundColor="primary">Column 2</Column>
		</Columns>
		<Columns backgroundColor="light">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
		<Columns backgroundColor="primary">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>

	<!-- Used to manually test ignoring of attributes -->
	<Container>
		<Columns backgroundColor="light">
			<Column backgroundColor="light">Column 1</Column>
			<Column backgroundColor="light">Column 2</Column>
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
