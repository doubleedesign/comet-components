<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = [
<<<TYCHO
<Container testId="example-1">
    <Columns>
    	<Column>Column 1</Column>
    	<Column backgroundColor="light">Column 2</Column>
    	<Column>Column 3</Column>
	</Columns>
</Container>
TYCHO,

<<<TYCHO
<Container testId="example-2" backgroundColor="light">
    <Columns backgroundColor="light">
    	<Column backgroundColor="light">Column 1</Column>
    	<Column backgroundColor="primary">Column 2</Column>
    	<Column>Column 3</Column>
	</Columns>
</Container>
TYCHO,

<<<TYCHO
<Container testId="example-3">
    <Columns backgroundColor="light">
    	<Column backgroundColor="light">Column 1</Column>
    	<Column>Column 2</Column>
    	<Column>Column 3</Column>
	</Columns>
</Container>
TYCHO,

<<<TYCHO
<Container testId="example-4" backgroundColor="light">
    <Columns>
    	<Column backgroundColor="light">Column 1</Column>
    	<Column>Column 2</Column>
    	<Column>Column 3</Column>
	</Columns>
</Container>
TYCHO,

<<<TYCHO
<Container testId="example-5" backgroundColor="light">
    <Columns backgroundColor="dark">
    	<Column backgroundColor="light">Column 1</Column>
    	<Column>Column 2</Column>
    	<Column>Column 3</Column>
	</Columns>
</Container>
TYCHO
];

try {
	foreach($page as $template) {
		$component = TychoService::parse($template);
		$component->render();
	}
}
catch (Exception $e) {
	echo $e->getMessage();
}
