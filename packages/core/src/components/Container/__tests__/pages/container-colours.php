<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Container testId="example-1">
		<Paragraph>Container no background</Paragraph>
	</Container>
	<Container testId="example-1b">
		<Paragraph>Container no background</Paragraph>
	</Container>
	
	<Container backgroundColor="light" testId="example-2">
		<Paragraph>Container light background</Paragraph>
	</Container>
	<Container backgroundColor="light" testId="example-2b">
		<Paragraph>Container light background</Paragraph>
	</Container>
	
	<Container backgroundColor="white" testId="example-4">
		<Paragraph>Page section, first thing with a background, but it's the same as the global background</Paragraph>
	</Container>
	
	<Container backgroundColor="dark" testId="example-3">
		<Paragraph>Container dark background</Paragraph>
	</Container>
	<Container backgroundColor="primary" testId="example-3b">
		<Paragraph>Container primary background</Paragraph>
	</Container>
	
	<Container backgroundColor="white" withWrapper="false" testId="example-5">
		<Paragraph>No page section wrapper, first thing with a background, but it's the same as the global background</Paragraph>
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
