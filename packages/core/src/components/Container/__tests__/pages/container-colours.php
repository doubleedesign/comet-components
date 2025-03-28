<?php
use Doubleedesign\Comet\Core\{TychoService};

$page = [
	<<<TYCHO
<Group testId="example-1">
	<Container>
		<Paragraph>Container no background</Paragraph>
	</Container>
	<Container>
		<Paragraph>Container no background</Paragraph>
	</Container>
</Group>
TYCHO,
	<<<TYCHO
<Group testId="example-2">
	<Container backgroundColor="light">
		<Paragraph>Container light background</Paragraph>
	</Container>
	<Container backgroundColor="light">
		<Paragraph>Container light background</Paragraph>
	</Container>
</Group>
TYCHO,
	<<<TYCHO
<Group testId="example-3">
	<Container backgroundColor="dark">
		<Paragraph>Container dark background</Paragraph>
	</Container>
	<Container backgroundColor="primary">
		<Paragraph>Container primary background</Paragraph>
	</Container>
</Group>
TYCHO,
	<<<TYCHO
<Group testId="example-4">
	<Container>
		<Paragraph>Container dark background</Paragraph>
	</Container>
	<Container backgroundColor="white">
		<Paragraph>Container primary background</Paragraph>
	</Container>
</Group>
TYCHO,

];

try {
	foreach($page as $template) {
		$component = TychoService::parse($template);
		$component->render();
	}
}
catch(Exception $e) {
	echo $e->getMessage();
}
