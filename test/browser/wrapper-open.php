<?php
// Autoload dependencies using Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Allow Storybook to access this server
$storybook = 'http://localhost:6006';
if(isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === $storybook) {
    header("Access-Control-Allow-Origin: " . $storybook);
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
