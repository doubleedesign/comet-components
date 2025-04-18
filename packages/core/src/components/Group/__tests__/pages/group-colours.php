<?php
use Doubleedesign\Comet\Core\{Assets, TychoService};

$page = <<<TYCHO
<TychoTemplate xmlns="schema/components.xsd">
	<Container testId="example-1" withWrapper="false">
		<Group>Adjacent groups no background</Group>
		<Group>...in a basic container</Group>
	</Container>
	<Container testId="example-2" withWrapper="false">
		<Group backgroundColor="light">Adjacent light background</Group>
		<Group backgroundColor="light">...in a basic container</Group>
	</Container>
	<Container testId="example-3" withWrapper="false">
		<Group backgroundColor="dark">Group dark background</Group>
		<Group backgroundColor="primary">Group primary background</Group>
	</Container>
	<Group testId="example-4">
		<Group>
			<Paragraph>Nested group no backgrounds</Paragraph>
		</Group>
	</Group>
	<Group backgroundColor="light" testId="example-5">
		<Group backgroundColor="light">
			<Paragraph>Nested group same background on both</Paragraph>
		</Group>
	</Group>
	<Group backgroundColor="dark" testId="example-6">
		<Group backgroundColor="primary">
			<Paragraph>Nested group different backgrounds</Paragraph>
		</Group>
	</Group>
	<Group backgroundColor="light" testId="example-7">
		<Group backgroundColor="primary">
			<Paragraph>Two nested groups</Paragraph>
		</Group>
		<Group backgroundColor="primary">
			<Paragraph>...same background as each other, different to parent</Paragraph>
		</Group>
	</Group>
	<Group testId="example-8" backgroundColor="light">
		<Group backgroundColor="light">
			<Paragraph>Two nested groups</Paragraph>
		</Group>
		<Group backgroundColor="light">
			<Paragraph>...same background as the parent</Paragraph>
		</Group>
	</Group>
	<Group testId="example-9">
		<Group backgroundColor="white">
			<Paragraph>Nested, same background as global</Paragraph>
		</Group>
	</Group>
	<Group backgroundColor="primary" testId="example-10">
		<Group backgroundColor="primary">
			<Paragraph>Nested, same background as parent</Paragraph>
		</Group>
		<Group backgroundColor="primary">
			<Paragraph>Nested, same background as parent</Paragraph>
		</Group>
		<Group backgroundColor="light">
			<Paragraph>Nested, different background to parent</Paragraph>
		</Group>
	</Group>
	<Group backgroundColor="secondary" testId="example-11">
		<Group backgroundColor="secondary">
			<Paragraph>Nested, both same background as parent</Paragraph>
		</Group>
		<Group backgroundColor="secondary">
			<Paragraph>Nested, both same background as parent</Paragraph>
		</Group>
	</Group>
	<Group testId="example-12">
		<Group backgroundColor="light">
			<Paragraph>Nested, same as each other with no background set on parent</Paragraph>
		</Group>
		<Group backgroundColor="light">
			<Paragraph>Nested, same as each other with no background set on parent</Paragraph>
		</Group>
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

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 7) . '/test/browser/wrapper-close.php';
}

