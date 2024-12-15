<?php
// Get the current directory and replace backslashes with forward slashes
$currentDir = str_replace('\\', '/', __DIR__);

// Create/update php.ini file with paths to the files to prepend and append to each component template file to make them complete webpages
// (e.g., add doctype, html, head, and body tags; these are PHP not HTML so we can also include PHP in the header to call in the functions that our components need)
$phpIniContent = "
auto_prepend_file = $currentDir/wrapper-open.php
auto_append_file = $currentDir/wrapper-close.php
";

// Path for the php.ini file
$phpIniPath = $currentDir . '/php.ini';

// Write the content to the file
file_put_contents($phpIniPath, $phpIniContent);

// Port to use for the web server that will serve the components
$port = 6001;

// Kill any hanging previous PHP server processes (otherwise php.ini caching issues can happen)
exec("netstat -ano | findstr :$port", $output);
if (!empty($output)) {
    foreach ($output as $line) {
        if (preg_match('/\s+(\d+)\s*$/', $line, $matches)) {
            $pid = $matches[1];
            exec("taskkill /PID $pid /F");
        }
    }
}

// Start the server, using the components directory (in the same folder as this file) as the web root
$cmd = "php -S localhost:$port -c $phpIniPath -t $currentDir/components";
exec($cmd);
