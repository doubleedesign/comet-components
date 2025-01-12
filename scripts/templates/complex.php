<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

$component = new ComplexComponent($attributes, $innerComponents);
$component->render();
