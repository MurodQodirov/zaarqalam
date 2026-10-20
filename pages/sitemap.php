<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/seo.php';

$base = site_base_url();
$today = date('Y-m-d');

$urls = [
    ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
    ['loc' => '/services', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['loc' => '/portfolio', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['loc' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/contact', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/privacy-policy', 'priority' => '0.3', 'changefreq' => 'yearly'],
];

foreach (services_data() as $service) {
    if (!empty($service['slug'])) {
        $urls[] = [
            'loc' => '/services/' . $service['slug'],
            'priority' => '0.8',
            'changefreq' => 'weekly',
        ];
    }
}

foreach (portfolio_data() as $project) {
    if (!empty($project['slug'])) {
        $urls[] = [
            'loc' => '/portfolio/' . $project['slug'],
            'priority' => '0.6',
            'changefreq' => 'monthly',
        ];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $item): ?>
  <url>
    <loc><?= htmlspecialchars($base . $item['loc'], ENT_XML1) ?></loc>
    <lastmod><?= htmlspecialchars($today, ENT_XML1) ?></lastmod>
    <changefreq><?= htmlspecialchars($item['changefreq'], ENT_XML1) ?></changefreq>
    <priority><?= htmlspecialchars($item['priority'], ENT_XML1) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
