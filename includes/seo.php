<?php

declare(strict_types=1);

function site_base_url(): string
{
    if (defined('SITE_URL') && SITE_URL !== '') {
        return rtrim((string) SITE_URL, '/');
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'zaarqalam.uz');

    return $scheme . '://' . $host;
}

function absolute_url(string $path = '/'): string
{
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return site_base_url() . '/' . ltrim($path, '/');
}

function seo_defaults(array $site = []): array
{
    $name = (string) ($site['name'] ?? 'Zaarqalam');
    $phone = (string) ($site['phone'] ?? '+998 99 200 33 53');
    $address = (string) ($site['address'] ?? 'Sirdaryo viloyati, Guliston shahar');

    return [
        'brand' => 'Zaarqalam',
        'name' => $name,
        'title' => 'Zaarqalam — Guliston logo, brending va poligrafiya',
        'description' => (string) ($site['seo_description'] ?? 'Zaarqalam — Gulistonda logo xizmati, brending, qadoq dizayni va poligrafiya. Sirdaryo viloyati, Guliston shaharda vizitka, flayer, menyu, katalog va merch dizayni.'),
        'keywords' => 'zaarqalam, zarqalam, guliston logo, guliston logo xizmati, gulistonda poligrafiya, sirdaryo poligrafiya, guliston brending, guliston vizitka, guliston qadoq, logo dizayn guliston',
        'ogImage' => absolute_url('/public/logo/1.png'),
        'locale' => 'uz_UZ',
        'geoRegion' => 'UZ-SI',
        'geoPlacename' => 'Guliston, Sirdaryo',
        'phone' => $phone,
        'phoneRaw' => (string) ($site['phone_raw'] ?? '+998992003353'),
        'email' => (string) ($site['email'] ?? 'info@zarqalam.uz'),
        'address' => $address,
        'telegram' => (string) ($site['telegram'] ?? 'https://t.me/zaarqalam'),
        'instagram' => (string) ($site['instagram'] ?? 'https://instagram.com/zaarqalam'),
    ];
}

function seo_faq_items(): array
{
    return [
        [
            'q' => 'Gulistonda logo xizmati qayerda bor?',
            'a' => 'Zaarqalam Guliston shahrida logo va brending xizmatini taklif qiladi: logotip, firma uslubi, ranglar palitrasi va brend identifikatsiyasi.',
        ],
        [
            'q' => 'Gulistonda poligrafiya xizmati bormi?',
            'a' => 'Ha. Zaarqalam Guliston va Sirdaryo bo‘ylab poligrafiya xizmatlarini beradi: vizitka, flayer, menyu, katalog, buklet, etiketka, qadoq va reklama belgilari.',
        ],
        [
            'q' => 'Zaarqalam nima qiladi?',
            'a' => 'Zaarqalam (Zarqalam) — Gulistondagi brending va poligrafiya studiyasi. Logo, qadoq, korporativ sovg‘alar, merch va bosma mahsulotlar dizaynini tayyorlaydi.',
        ],
        [
            'q' => 'Sirdaryoda qadoq va merch dizayni qilish mumkinmi?',
            'a' => 'Mumkin. Zaarqalam Sirdaryo viloyati, Guliston shaharda qadoq, futbolka, kepka va korporativ sovg‘a to‘plamlari uchun dizayn va chop etishga tayyor fayllarni beradi.',
        ],
        [
            'q' => 'Buyurtmani qanday beraman?',
            'a' => 'Saytdagi forma orqali yoki Telegram @zaarqalam va telefon +998 99 200 33 53 orqali murojaat qiling. Tez orada bog‘lanamiz.',
        ],
    ];
}

function render_seo_head(array $vars = []): void
{
    $site = $vars['site'] ?? site_data();
    $seo = array_merge(seo_defaults($site), $vars['seo'] ?? []);

    $pageTitle = (string) ($vars['pageTitle'] ?? $seo['title']);
    $metaDescription = (string) ($vars['metaDescription'] ?? $seo['description']);
    $canonicalPath = (string) ($vars['canonical'] ?? current_path());
    $canonical = absolute_url($canonicalPath === '/' ? '/' : $canonicalPath);
    $ogImage = absolute_url((string) ($vars['ogImage'] ?? $seo['ogImage']));
    $robots = (string) ($vars['robots'] ?? 'index, follow, max-image-preview:large');
    $noindexAdmin = str_starts_with(current_path(), '/admin');
    if ($noindexAdmin) {
        $robots = 'noindex, nofollow';
    }

    $schemas = [];

    $schemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Zaarqalam',
        'alternateName' => ['Zarqalam', 'zaarqalam', 'zaarqalam guliston'],
        'url' => site_base_url() . '/',
        'logo' => absolute_url('/public/logo/1.png'),
        'email' => $seo['email'],
        'telephone' => $seo['phoneRaw'],
        'sameAs' => array_values(array_filter([
            $seo['telegram'],
            $seo['instagram'],
        ])),
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => $seo['phoneRaw'],
            'contactType' => 'customer service',
            'areaServed' => 'UZ',
            'availableLanguage' => ['uz', 'ru'],
        ],
        'areaServed' => [
            ['@type' => 'AdministrativeArea', 'name' => 'Sirdaryo'],
            ['@type' => 'City', 'name' => 'Guliston'],
        ],
    ];

    $schemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => site_base_url() . '/#localbusiness',
        'name' => 'Zaarqalam',
        'image' => absolute_url('/public/logo/1.png'),
        'url' => site_base_url() . '/',
        'telephone' => $seo['phoneRaw'],
        'email' => $seo['email'],
        'priceRange' => '$$',
        'address' => [
            '@type' => 'PostalAddress',
            'addressCountry' => 'UZ',
            'addressRegion' => 'Sirdaryo',
            'addressLocality' => 'Guliston',
            'streetAddress' => $seo['address'],
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 40.4897,
            'longitude' => 68.7842,
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
            ],
            'opens' => '09:00',
            'closes' => '21:00',
        ],
        'areaServed' => [
            ['@type' => 'AdministrativeArea', 'name' => 'Sirdaryo'],
            ['@type' => 'City', 'name' => 'Guliston'],
        ],
        'description' => $seo['description'],
        'knowsAbout' => [
            'logo dizayn',
            'brending',
            'poligrafiya',
            'qadoq dizayni',
            'vizitka',
            'Guliston logo xizmati',
            'Sirdaryo poligrafiya',
        ],
    ];

    $schemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Zaarqalam',
        'url' => site_base_url() . '/',
        'inLanguage' => 'uz',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Zaarqalam',
        ],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => site_base_url() . '/portfolio?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $faqEntities = [];
    foreach (seo_faq_items() as $item) {
        $faqEntities[] = [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ];
    }
    $schemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqEntities,
    ];

    $serviceList = [];
    foreach (services_data() as $service) {
        if (empty($service['title'])) {
            continue;
        }
        $serviceList[] = [
            '@type' => 'Offer',
            'itemOffered' => [
                '@type' => 'Service',
                'name' => $service['title'],
                'description' => $service['short'] ?? ($service['description'] ?? ''),
                'areaServed' => [
                    ['@type' => 'City', 'name' => 'Guliston'],
                    ['@type' => 'AdministrativeArea', 'name' => 'Sirdaryo'],
                ],
                'provider' => [
                    '@type' => 'LocalBusiness',
                    'name' => 'Zaarqalam',
                ],
            ],
        ];
    }
    if ($serviceList) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'OfferCatalog',
            'name' => 'Zaarqalam xizmatlari — Guliston poligrafiya',
            'itemListElement' => $serviceList,
        ];
    }

    ?>
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="<?= e($seo['keywords']) ?>">
    <meta name="author" content="Zaarqalam">
    <meta name="robots" content="<?= e($robots) ?>">
    <meta name="googlebot" content="<?= e($robots) ?>">
    <meta name="language" content="uz">
    <meta name="geo.region" content="<?= e($seo['geoRegion']) ?>">
    <meta name="geo.placename" content="<?= e($seo['geoPlacename']) ?>">
    <meta name="geo.position" content="40.4897;68.7842">
    <meta name="ICBM" content="40.4897, 68.7842">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <link rel="alternate" hreflang="uz" href="<?= e($canonical) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e(site_base_url() . '/') ?>">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="<?= e($seo['locale']) ?>">
    <meta property="og:site_name" content="Zaarqalam">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">

    <?php foreach ($schemas as $schema): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
    <?php endforeach; ?>
    <?php
}
