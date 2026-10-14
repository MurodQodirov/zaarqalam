<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function load_json(string $filename): array
{
    $path = DATA_PATH . '/' . $filename;
    if (!is_file($path)) {
        return [];
    }

    $content = file_get_contents($path);
    if ($content === false || $content === '') {
        return [];
    }

    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function save_json(string $filename, array $data): bool
{
    $path = DATA_PATH . '/' . $filename;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        return false;
    }

    return file_put_contents($path, $json . "\n", LOCK_EX) !== false;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function site_data(): array
{
    static $data = null;
    if ($data === null) {
        $data = load_json('site.json');
    }
    return $data;
}

function services_data(): array
{
    static $data = null;
    if ($data === null) {
        $data = load_json('services.json');
    }
    return $data;
}

function portfolio_data(): array
{
    static $data = null;
    if ($data === null) {
        $data = load_json('portfolio.json');
    }
    return $data;
}

function get_service(string $slug): ?array
{
    foreach (services_data() as $service) {
        if (($service['slug'] ?? '') === $slug) {
            return $service;
        }
    }
    return null;
}

function get_project(string $slug): ?array
{
    foreach (portfolio_data() as $project) {
        if (($project['slug'] ?? '') === $slug) {
            return $project;
        }
    }
    return null;
}

function projects_by_category(?string $category = null, int $limit = 0): array
{
    $projects = portfolio_data();

    if ($category && $category !== 'all') {
        $projects = array_values(array_filter(
            $projects,
            static fn(array $p): bool => ($p['category'] ?? '') === $category
        ));
    }

    if ($limit > 0) {
        $projects = array_slice($projects, 0, $limit);
    }

    return $projects;
}

function related_projects(array $project, int $limit = 3): array
{
    $related = [];
    foreach (portfolio_data() as $item) {
        if (($item['slug'] ?? '') === ($project['slug'] ?? '')) {
            continue;
        }
        if (($item['category'] ?? '') === ($project['category'] ?? '')) {
            $related[] = $item;
        }
        if (count($related) >= $limit) {
            break;
        }
    }

    if (count($related) < $limit) {
        foreach (portfolio_data() as $item) {
            if (($item['slug'] ?? '') === ($project['slug'] ?? '')) {
                continue;
            }
            $exists = false;
            foreach ($related as $r) {
                if (($r['slug'] ?? '') === ($item['slug'] ?? '')) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $related[] = $item;
            }
            if (count($related) >= $limit) {
                break;
            }
        }
    }

    return $related;
}

function category_label(string $category): string
{
    $service = get_service($category);
    if ($service) {
        return (string) ($service['title'] ?? $category);
    }

    $labels = [
        'other' => 'Boshqa',
    ];

    return $labels[$category] ?? $category;
}

function current_path(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH);
    $path = is_string($path) ? $path : '/';
    $path = rtrim($path, '/') ?: '/';
    return $path;
}

function is_active(string $path): bool
{
    $current = current_path();
    if ($path === '/') {
        return $current === '/';
    }
    return $current === $path || str_starts_with($current, $path . '/');
}

function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

function page_url(string $path = '/'): string
{
    $path = '/' . ltrim($path, '/');
    return $path === '//' ? '/' : $path;
}

function render_page(string $view, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    $site = site_data();
    $services = services_data();

    require ROOT_PATH . '/includes/header.php';
    require ROOT_PATH . '/pages/' . $view . '.php';
    require ROOT_PATH . '/includes/footer.php';
}

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function sanitize_phone(string $phone): string
{
    return preg_replace('/[^0-9+\-\s()]/', '', $phone) ?? '';
}

function validate_order(array $input): array
{
    $errors = [];

    $name = trim((string) ($input['name'] ?? ''));
    $phone = trim((string) ($input['phone'] ?? ''));
    $service = trim((string) ($input['service'] ?? ''));
    $quantity = trim((string) ($input['quantity'] ?? ''));
    $message = trim((string) ($input['message'] ?? ''));

    if ($name === '' || mb_strlen($name) < 2) {
        $errors['name'] = 'Ismni kiriting.';
    }

    if ($phone === '' || mb_strlen(preg_replace('/\D/', '', $phone) ?? '') < 9) {
        $errors['phone'] = 'To‘g‘ri telefon raqamini kiriting.';
    }

    if ($service === '') {
        $errors['service'] = 'Xizmat turini tanlang.';
    }

    return [
        'errors' => $errors,
        'data' => [
            'name' => $name,
            'phone' => sanitize_phone($phone),
            'service' => $service,
            'quantity' => $quantity,
            'message' => $message,
        ],
    ];
}
