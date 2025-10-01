<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Separator color="dark"/>
	<Container testId="example-1">
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
	
	<Separator color="dark"/>
	<Container testId="example-2">
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
		<Columns backgroundColor="accent">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-3" backgroundColor="light">
		<Columns backgroundColor="light">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-4" backgroundColor="light">
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column backgroundColor="accent">Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-5" backgroundColor="dark">
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
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
	
	<Separator color="dark"/>
	<Container testId="example-6" backgroundColor="light">
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
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
	
	<Separator color="dark"/>
	<Container testId="example-7" backgroundColor="primary">
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
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
	
	<Separator color="dark"/>
	<Container testId="example-8" backgroundColor="primary">
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
		<Columns backgroundColor="light">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-9">
		<Columns>
			<Column backgroundColor="white">Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-10">
		<Columns backgroundColor="white">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-11" backgroundColor="light">
		<Columns>
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>

</TychoTemplate>
TYCHO;

try {
    $components = TychoService::parse($page);
    foreach ($components as $component) {
        $component->render();
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}
