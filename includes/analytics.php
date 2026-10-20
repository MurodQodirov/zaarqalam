<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

function visits_file(): string
{
    return DATA_PATH . '/visits.json';
}

function load_visits(): array
{
    $path = visits_file();
    if (!is_file($path)) {
        return ['days' => [], 'seen' => ['date' => '', 'ids' => []]];
    }

    $raw = file_get_contents($path);
    $data = json_decode($raw ?: '', true);
    if (!is_array($data)) {
        return ['days' => [], 'seen' => ['date' => '', 'ids' => []]];
    }

    return [
        'days' => is_array($data['days'] ?? null) ? $data['days'] : [],
        'seen' => is_array($data['seen'] ?? null) ? $data['seen'] : ['date' => '', 'ids' => []],
    ];
}

function save_visits(array $data): bool
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return false;
    }
    return file_put_contents(visits_file(), $json . "\n", LOCK_EX) !== false;
}

function is_bot_user_agent(string $ua): bool
{
    if ($ua === '') {
        return true;
    }

    return (bool) preg_match(
        '/bot|crawl|spider|slurp|facebookexternalhit|preview|wget|curl|python-requests|scrapy|headless/i',
        $ua
    );
}

function visitor_id(): string
{
    $cookie = 'zq_vid';
    if (!empty($_COOKIE[$cookie]) && preg_match('/^[a-f0-9]{16,64}$/', (string) $_COOKIE[$cookie])) {
        return (string) $_COOKIE[$cookie];
    }

    try {
        $id = bin2hex(random_bytes(16));
    } catch (Throwable) {
        $id = hash('sha256', uniqid((string) mt_rand(), true));
    }

    setcookie($cookie, $id, [
        'expires' => time() + 60 * 60 * 24 * 365,
        'path' => '/',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    $_COOKIE[$cookie] = $id;

    return $id;
}

function prune_visits(array $data, int $keepDays = 90): array
{
    $cutoff = date('Y-m-d', strtotime('-' . $keepDays . ' days') ?: time());
    $days = [];
    foreach (($data['days'] ?? []) as $day => $row) {
        if ((string) $day >= $cutoff && is_array($row)) {
            $days[(string) $day] = [
                'views' => (int) ($row['views'] ?? 0),
                'visitors' => (int) ($row['visitors'] ?? 0),
            ];
        }
    }
    ksort($days);
    $data['days'] = $days;
    return $data;
}

function track_page_visit(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
        return;
    }

    $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (is_bot_user_agent($ua)) {
        return;
    }

    $path = current_path();
    if (str_starts_with($path, '/admin') || str_starts_with($path, '/api')) {
        return;
    }

    $today = date('Y-m-d');
    $vid = visitor_id();
    $data = load_visits();

    if (($data['seen']['date'] ?? '') !== $today) {
        $data['seen'] = ['date' => $today, 'ids' => []];
    }

    if (!isset($data['days'][$today]) || !is_array($data['days'][$today])) {
        $data['days'][$today] = ['views' => 0, 'visitors' => 0];
    }

    $data['days'][$today]['views'] = (int) ($data['days'][$today]['views'] ?? 0) + 1;

    $ids = is_array($data['seen']['ids'] ?? null) ? $data['seen']['ids'] : [];
    if (!isset($ids[$vid])) {
        $ids[$vid] = 1;
        $data['seen']['ids'] = $ids;
        $data['days'][$today]['visitors'] = (int) ($data['days'][$today]['visitors'] ?? 0) + 1;
    }

    $data = prune_visits($data, 90);
    save_visits($data);
}

function visits_summary(int $daysBack = 14): array
{
    $data = load_visits();
    $days = is_array($data['days'] ?? null) ? $data['days'] : [];
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day') ?: time());
    $monthPrefix = date('Y-m');

    $series = [];
    $maxViews = 1;
    $totalViews = 0;
    $totalVisitors = 0;
    $monthViews = 0;
    $monthVisitors = 0;

    for ($i = $daysBack - 1; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime('-' . $i . ' days') ?: time());
        $row = is_array($days[$day] ?? null) ? $days[$day] : [];
        $views = (int) ($row['views'] ?? 0);
        $visitors = (int) ($row['visitors'] ?? 0);
        $series[] = [
            'date' => $day,
            'label' => date('d.m', strtotime($day) ?: time()),
            'views' => $views,
            'visitors' => $visitors,
        ];
        $maxViews = max($maxViews, $views, $visitors);
        $totalViews += $views;
        $totalVisitors += $visitors;
        if (str_starts_with($day, $monthPrefix)) {
            $monthViews += $views;
            $monthVisitors += $visitors;
        }
    }

    $todayRow = is_array($days[$today] ?? null) ? $days[$today] : [];
    $yesterdayRow = is_array($days[$yesterday] ?? null) ? $days[$yesterday] : [];

    return [
        'today_views' => (int) ($todayRow['views'] ?? 0),
        'today_visitors' => (int) ($todayRow['visitors'] ?? 0),
        'yesterday_views' => (int) ($yesterdayRow['views'] ?? 0),
        'yesterday_visitors' => (int) ($yesterdayRow['visitors'] ?? 0),
        'month_views' => $monthViews,
        'month_visitors' => $monthVisitors,
        'period_views' => $totalViews,
        'period_visitors' => $totalVisitors,
        'series' => $series,
        'max' => $maxViews,
        'days_back' => $daysBack,
    ];
}
