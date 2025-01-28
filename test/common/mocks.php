<?php
namespace Doubleedesign\Comet\TestUtils;
use Doubleedesign\Comet\Core\{Heading, Paragraph};

$heading = (new Heading([], 'Hello World'))->to_array();
$paragraph = (new Paragraph([], 'This is a simple paragraph'))->to_array();

define("Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_SIMPLE", [
	$heading,
	$paragraph,
]);
