<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file acts as a router for shared hosting environments
 * where you cannot set the document root to the /public folder.
 * 
 * It redirects all requests to the actual index.php in /public folder.
 *
 * @package  Laravel
 */

// Define the path to the public directory
define('LARAVEL_START', microtime(true));

// Check if the request is for a static file in the public directory
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If the file exists in the public directory and is not a PHP file, serve it
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri) && !is_dir(__DIR__.'/public'.$uri)) {
    // Check if it's not a PHP file (for security)
    $extension = pathinfo($uri, PATHINFO_EXTENSION);
    
    if ($extension !== 'php') {
        // Serve the static file
        $file = __DIR__.'/public'.$uri;
        $mimeType = mime_content_type($file);
        
        header('Content-Type: '.$mimeType);
        header('Content-Length: '.filesize($file));
        readfile($file);
        exit;
    }
}

// Otherwise, load the Laravel application
require_once __DIR__.'/public/index.php';
