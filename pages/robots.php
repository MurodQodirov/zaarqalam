<?php

declare(strict_types=1);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim($scheme . '://' . $host, '/');

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /data/\n";
echo "Disallow: /includes/\n";
echo "Disallow: /api/\n";
echo "Disallow: /public/uploads/\n";
echo "\n";
echo "Sitemap: {$base}/sitemap.xml\n";
