<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Group testId="example-1">
		<Container>
			<Paragraph>Container no background</Paragraph>
		</Container>
		<Container>
			<Paragraph>Container no background</Paragraph>
		</Container>
	</Group>
	<Group testId="example-2">
		<Container backgroundColor="light">
			<Paragraph>Container light background</Paragraph>
		</Container>
		<Container backgroundColor="light">
			<Paragraph>Container light background</Paragraph>
		</Container>
	</Group>
	<Group testId="example-3">
		<Container backgroundColor="dark">
			<Paragraph>Container dark background</Paragraph>
		</Container>
		<Container backgroundColor="primary">
			<Paragraph>Container primary background</Paragraph>
		</Container>
	</Group>
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
