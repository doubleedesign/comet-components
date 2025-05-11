<?php
use Doubleedesign\Comet\Core\Table;

// Attribute keys from component JSON definition
$attributeKeys = ['allowStacking', 'caption', 'sticky'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

// $component = new Table($attributes, $innerComponents);
// $component->render();

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if (getenv('SERVER_NAME') === 'comet-components.test') {
    require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}
