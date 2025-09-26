<?php
// Local CORS config for development and testing
// (Production config is in .htaccess)
header("Access-Control-Allow-Origin: *", true);
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With", true);
header("Access-Control-Allow-Methods: GET, OPTIONS", true);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Cache busting
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
