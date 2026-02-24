<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// Check if file exists as is (for css, js, images)
// Use realpath to avoid any path traversal issues
$path = realpath(__DIR__ . '/' . $uri);
if ($uri && $path && file_exists($path) && is_file($path)) {
    return false;
}

// Check if it's a php file without extension
// Remove trailing slash if present
$clean_uri = rtrim($uri, '/');
if ($clean_uri && file_exists(__DIR__ . '/' . $clean_uri . '.php')) {
    require_once __DIR__ . '/' . $clean_uri . '.php';
    exit;
}

// Default to index.php for root or non-matches
include_once 'index.php';
?>
