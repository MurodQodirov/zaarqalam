<?php
$site = $site ?? site_data();
$pageTitle = $pageTitle ?? ($site['name'] ?? 'Zarqalam');
$metaDescription = $metaDescription ?? ($site['description'] ?? '');
$ogImage = $ogImage ?? asset('public/images/portfolio/noir-coffee.svg');
$canonical = $canonical ?? page_url(current_path());
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <link rel="icon" href="<?= e(asset('public/logo/favicon.png')) ?>" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('public/css/style.css')) ?>">
</head>
<body>
<header class="site-header" data-site-header>
    <div class="container header-inner">
        <div class="header-left">
            <a class="logo" href="<?= e(page_url('/')) ?>" aria-label="zaarqalam bosh sahifa">
                <img src="<?= e(asset('public/logo/1.png')) ?>" alt="zaarqalam" width="1848" height="330" class="logo-img">
            </a>

            <nav class="nav" aria-label="Asosiy menyu">
                <a href="<?= e(page_url('/#services')) ?>" data-nav="services">Xizmatlar</a>
                <a href="<?= e(page_url('/#portfolio')) ?>" data-nav="portfolio">Portfolio</a>
                <a href="<?= e(page_url('/#about')) ?>" data-nav="about">Biz haqimizda</a>
                <a href="<?= e(page_url('/#contact')) ?>" data-nav="contact">Aloqa</a>
            </nav>
        </div>

        <div class="header-actions">
            <a class="btn btn--primary" href="<?= e(page_url('/#contact')) ?>">Buyurtma berish</a>
            <button class="menu-toggle" type="button" data-menu-toggle aria-label="Menyuni ochish" aria-expanded="false" aria-controls="mobile-nav">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <div class="mobile-nav" id="mobile-nav" data-mobile-nav>
        <div class="container">
            <a href="<?= e(page_url('/#services')) ?>">Xizmatlar</a>
            <a href="<?= e(page_url('/#portfolio')) ?>">Portfolio</a>
            <a href="<?= e(page_url('/#about')) ?>">Biz haqimizda</a>
            <a href="<?= e(page_url('/#contact')) ?>">Aloqa</a>
            <a class="btn btn--primary" href="<?= e(page_url('/#contact')) ?>">Buyurtma berish</a>
        </div>
    </div>
</header>
<main>
