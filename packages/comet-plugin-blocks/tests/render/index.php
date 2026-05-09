<?php
header('Content-Type: text/html');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once __DIR__ . '/../../vendor/autoload.php';
require_once 'mocks.php';

$input = json_decode(file_get_contents('php://input'), true);
$blockSlug = str_replace('comet/', '', $input['name']);
$block = $input['attributes'] ?? [];

// TODO Load the bundled core CSS and JS
ob_start();
include __DIR__ . "/../../src/blocks/$blockSlug/render.php";
echo ob_get_clean();
