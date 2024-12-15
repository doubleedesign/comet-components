<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Components\ComplexComponent;

$component = new ComplexComponent($attributes, $innerComponents);
$component->render();
