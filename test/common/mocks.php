<?php
namespace Doubleedesign\Comet\TestUtils;
use Doubleedesign\Comet\Core\{Heading, Paragraph, Columns, Column, Button};

$heading = (new Heading([], 'Hello World'));
$paragraph = (new Paragraph([], 'This is a simple paragraph'));

define("Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_SIMPLE", [
	$heading,
	$paragraph,
]);

const MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT = [
	new Paragraph([], 'This is a plain block of example text courtesy of Friends Ipsum. Three bathrooms in this place, and I threw up in a coat closet. You can\'t just give up. Is that what a dinosaur would do? The messers become the messees. I understand why Superman is here, but why is there a porcupine at the Easter Bunny\'s funeral? I tell you, when I actually die, some people are gonna get seriously haunted.'),
];

const MOCK_INNER_COMPONENTS_BUTTONS = [
	new Button(['colorTheme' => 'primary'], 'Button 1'),
	new Button(['colorTheme' => 'primary', 'isOutline' => true], 'Button 2'),
];
