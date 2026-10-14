<?php

declare(strict_types=1);

/**
 * Router for PHP built-in server:
 * php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// Block direct access to sensitive directories
$blocked = ['/data/', '/includes/', '/pages/'];
foreach ($blocked as $prefix) {
    if (str_starts_with($uri, $prefix) || $uri === rtrim($prefix, '/')) {
        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Forbidden';
        exit;
    }
}

$file = __DIR__ . $uri;

if ($uri !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
