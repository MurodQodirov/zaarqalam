<?php

declare(strict_types=1);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim($scheme . '://' . $host, '/');

$urls = [
    '/',
    '/services',
    '/portfolio',
    '/about',
    '/contact',
    '/privacy-policy',
];

foreach (services_data() as $service) {
    if (!empty($service['slug'])) {
        $urls[] = '/services/' . $service['slug'];
    }
}

foreach (portfolio_data() as $project) {
    if (!empty($project['slug'])) {
        $urls[] = '/portfolio/' . $project['slug'];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
  <url>
    <loc><?= htmlspecialchars($base . $url, ENT_XML1) ?></loc>
    <changefreq>weekly</changefreq>
  </url>
<?php endforeach; ?>
</urlset>
