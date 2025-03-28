<?php
use Doubleedesign\Comet\Core\{TychoService};

$page =	<<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Container testId="example-1">
		<Group>Group no background</Group>
		<Group>Group no background</Group>
	</Container>
	<Container testId="example-2">
		<Group backgroundColor="light">Group light background</Group>
		<Group backgroundColor="light">Group light background</Group>
	</Container>
	<Container testId="example-3">
		<Group backgroundColor="dark">Group dark background</Group>
		<Group backgroundColor="primary">Group primary background</Group>
	</Container>
	<Container testId="example-4">
		<Group>
			<Group>
				<Paragraph>Nested group no background</Paragraph>
			</Group>
		</Group>
	</Container>
	<Container testId="example-5">
		<Group backgroundColor="light">
			<Group backgroundColor="light">
				<Paragraph>Nested group same background on both</Paragraph>
			</Group>
		</Group>
	</Container>
	<Container testId="example-6">
		<Group backgroundColor="dark">
			<Group backgroundColor="primary">
				<Paragraph>Nested group different backgrounds</Paragraph>
			</Group>
		</Group>
	</Container>
	<Container testId="example-7">
		<Group backgroundColor="light">
			<Group backgroundColor="primary">
				<Paragraph>Two nested groups</Paragraph>
			</Group>
			<Group backgroundColor="primary">
				<Paragraph>...same background as each other</Paragraph>
			</Group>
		</Group>
	</Container>
	<Container testId="example-8">
		<Group backgroundColor="light">
			<Group backgroundColor="light">
				<Paragraph>Two nested groups</Paragraph>
			</Group>
			<Group backgroundColor="light">
				<Paragraph>...same background as the parent</Paragraph>
			</Group>
		</Group>
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
