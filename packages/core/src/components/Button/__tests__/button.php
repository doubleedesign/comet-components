<?php
use Doubleedesign\Comet\Core\Button;

// Attribute keys fetched from component JSON definition
$attributeKeys = ['colorTheme', 'isOutline', 'tagName', 'href'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, function($key) use ($attributeKeys) {
    return in_array($key, $attributeKeys) && $_REQUEST[$key] !== '' && $_REQUEST[$key] !== 'false';
}, ARRAY_FILTER_USE_KEY);

$component = new Button($attributes, 'Lorem ipsum');
$component->render();

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if (getenv('SERVER_NAME') === 'comet-components.test') {
    require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}
