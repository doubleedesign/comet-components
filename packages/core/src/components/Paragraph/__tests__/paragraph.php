<?php
use Doubleedesign\Comet\Core\Paragraph;

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'textAlign', 'textColor'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$component = new Paragraph($attributes, 'This is a plain block of example text courtesy of Friends Ipsum.  was very drunk, and it was someone else\'s subconscious. If it\'s not a right angle, it\'s a wrong angle. They don’t know that we know they know we know. The messers become the messees. I\'m a gym member. I try to go four times a week, but I\'ve missed the last twelve hundred times. Should I use my invisibility to fight crime or for evil?');
$component->render();
