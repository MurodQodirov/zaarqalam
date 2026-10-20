<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/seo.php';

$base = site_base_url();

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /admin\n";
echo "Disallow: /admin/\n";
echo "Disallow: /api/\n";
echo "Disallow: /data/\n";
echo "Disallow: /includes/\n";
echo "Disallow: /pages/\n";
echo "Disallow: /public/uploads/\n";
echo "\n";
echo "User-agent: GPTBot\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: ChatGPT-User\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: Google-Extended\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: Googlebot\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: Bingbot\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: anthropic-ai\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: ClaudeBot\n";
echo "Allow: /\n";
echo "\n";
echo "User-agent: Bytespider\n";
echo "Allow: /\n";
echo "\n";
echo "Sitemap: {$base}/sitemap.xml\n";
echo "LLMs-txt: {$base}/llms.txt\n";
