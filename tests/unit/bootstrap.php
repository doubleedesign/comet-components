<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../packages/core/vendor/autoload.php';

require_once __DIR__ . '/../../vendor/antecedent/patchwork/Patchwork.php';


$packages = [
    __DIR__ . '/../../packages/core/tests/Pest.php',
    __DIR__ . '/../../packages/comet-calendar/tests/Pest.php',
];

foreach ($packages as $pestFile) {
    if (file_exists($pestFile)) {
        require_once $pestFile;
    }
}
