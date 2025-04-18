<?php
use Doubleedesign\Comet\Core\{TychoService, Assets};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Separator color="dark"/>
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
	
	<Separator color="dark"/>
	<Container testId="example-2" withWrapper="false">
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
	<Container testId="example-3" withWrapper="false" backgroundColor="light">
		<Columns backgroundColor="light">
			<Column>Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-4" withWrapper="false" backgroundColor="light">
		<Columns backgroundColor="dark">
			<Column>Column 1</Column>
			<Column backgroundColor="accent">Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-5" withWrapper="false" backgroundColor="dark">
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
	<Container testId="example-6" withWrapper="false" backgroundColor="light">
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
	<Container testId="example-7" withWrapper="false" backgroundColor="primary">
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
	<Container testId="example-8" withWrapper="false" backgroundColor="primary">
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
	<Container testId="example-9" withWrapper="false">
		<Columns>
			<Column backgroundColor="white">Column 1</Column>
			<Column>Column 2</Column>
		</Columns>
	</Container>
	
	<Separator color="dark"/>
	<Container testId="example-10" withWrapper="false">
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
	foreach($components as $component) {
		$component->render();
	}
}
catch(Exception $e) {
	echo $e->getMessage();
}

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 7) . '/test/browser/wrapper-close.php';
}

