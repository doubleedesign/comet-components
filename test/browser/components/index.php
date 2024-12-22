<?php
// Get all the files in this directory and create links to them
$files = glob(__DIR__ . '/*.php');
foreach ($files as $file) {
    $filename = basename($file);
    if ($filename === 'index.php') {
        continue;
    }
    echo "<a href=\"{$filename}\">{$filename}</a><br>";
}
