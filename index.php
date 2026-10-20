<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);
$path = is_string($path) ? $path : '/';
$path = rtrim($path, '/') ?: '/';

// Serve static files if requested directly under /public
if (str_starts_with($path, '/public/')) {
    return false;
}

if (str_starts_with($path, '/admin')) {
    require ROOT_PATH . '/admin/router.php';
    exit;
}

$site = site_data();

if ($path === '/') {
    render_page('home', [
        'pageTitle' => 'Zaarqalam - Brending va poligrafiya',
        'metaDescription' => $site['description'] ?? 'Logotip, qadoq, korporativ sovg‘alar va bosma mahsulotlar uchun kreativ yechimlar.',
        'projects' => portfolio_data(),
    ]);
    exit;
}

if ($path === '/services') {
    render_page('services', [
        'pageTitle' => 'Xizmatlar — Zarqalam',
        'metaDescription' => 'Logotip & branding, vizitka, flayer, menyu, katalog, buklet, etiketka, merch va boshqa poligrafiya xizmatlari.',
    ]);
    exit;
}

if (preg_match('#^/services/([a-z0-9\-]+)$#', $path, $matches)) {
    $service = get_service($matches[1]);
    if (!$service) {
        http_response_code(404);
        render_page('404', [
            'pageTitle' => 'Sahifa topilmadi — Zarqalam',
            'metaDescription' => 'So‘ralgan sahifa topilmadi.',
        ]);
        exit;
    }

    $relatedProjects = projects_by_category($service['slug'], 3);

    render_page('service-detail', [
        'pageTitle' => ($service['title'] ?? 'Xizmat') . ' — Zarqalam',
        'metaDescription' => $service['short'] ?? ($site['description'] ?? ''),
        'ogImage' => $service['image'] ?? null,
        'service' => $service,
        'relatedProjects' => $relatedProjects,
    ]);
    exit;
}

if ($path === '/portfolio') {
    render_page('portfolio', [
        'pageTitle' => 'Portfolio — Zarqalam',
        'metaDescription' => 'Zarqalamning tanlangan brending, qadoq, sovg‘a va poligrafiya loyihalari.',
        'projects' => portfolio_data(),
    ]);
    exit;
}

if (preg_match('#^/portfolio/([a-z0-9\-]+)$#', $path, $matches)) {
    $project = get_project($matches[1]);
    if (!$project) {
        http_response_code(404);
        render_page('404', [
            'pageTitle' => 'Loyiha topilmadi — Zarqalam',
            'metaDescription' => 'So‘ralgan loyiha topilmadi.',
        ]);
        exit;
    }

    render_page('portfolio-detail', [
        'pageTitle' => ($project['title'] ?? 'Loyiha') . ' — Zarqalam',
        'metaDescription' => $project['short'] ?? ($site['description'] ?? ''),
        'ogImage' => $project['image'] ?? null,
        'project' => $project,
        'relatedProjects' => related_projects($project, 3),
    ]);
    exit;
}

if ($path === '/about') {
    render_page('about', [
        'pageTitle' => 'Biz haqimizda — Zarqalam',
        'metaDescription' => $site['about_short'] ?? 'Zarqalam brending va poligrafiya studiyasi.',
    ]);
    exit;
}

if ($path === '/contact') {
    $preselect = isset($_GET['service']) ? (string) $_GET['service'] : '';
    render_page('contact', [
        'pageTitle' => 'Aloqa va buyurtma — Zarqalam',
        'metaDescription' => 'Zarqalam bilan bog‘laning yoki loyiha buyurtma bering.',
        'preselectService' => $preselect,
    ]);
    exit;
}

if ($path === '/privacy-policy') {
    render_page('privacy', [
        'pageTitle' => 'Maxfiylik siyosati — Zarqalam',
        'metaDescription' => 'Zarqalam maxfiylik siyosati va shaxsiy ma’lumotlarni qayta ishlash tartibi.',
    ]);
    exit;
}

if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    require __DIR__ . '/pages/sitemap.php';
    exit;
}

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    require __DIR__ . '/pages/robots.php';
    exit;
}

if ($path === '/llms.txt') {
    require __DIR__ . '/pages/llms.php';
    exit;
}

http_response_code(404);
render_page('404', [
    'pageTitle' => 'Sahifa topilmadi — Zarqalam',
    'metaDescription' => 'So‘ralgan sahifa topilmadi.',
]);
