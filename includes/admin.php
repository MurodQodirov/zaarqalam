<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function admin_session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function admin_credentials(): array
{
    $path = DATA_PATH . '/admin.json';
    if (!is_file($path)) {
        $seed = [
            'username' => 'admin',
            'password_hash' => password_hash('zarqalam2026', PASSWORD_DEFAULT),
        ];
        save_json('admin.json', $seed);
        return $seed;
    }

    $data = load_json('admin.json');
    return is_array($data) ? $data : [];
}

function admin_settings(): array
{
    $defaults = [
        'telegram_bot_token' => '',
        'telegram_chat_id' => '',
        'telegram_enabled' => false,
    ];
    $path = DATA_PATH . '/settings.json';
    if (!is_file($path)) {
        save_json('settings.json', $defaults);
        return $defaults;
    }
    $data = load_json('settings.json');
    return array_merge($defaults, is_array($data) ? $data : []);
}

function save_admin_settings(array $settings): bool
{
    return save_json('settings.json', [
        'telegram_bot_token' => trim((string) ($settings['telegram_bot_token'] ?? '')),
        'telegram_chat_id' => trim((string) ($settings['telegram_chat_id'] ?? '')),
        'telegram_enabled' => !empty($settings['telegram_enabled']),
    ]);
}

function admin_user(): ?array
{
    admin_session_start();
    if (empty($_SESSION['admin_logged_in'])) {
        return null;
    }

    $creds = admin_credentials();
    return [
        'username' => (string) ($creds['username'] ?? 'admin'),
        'name' => 'Administrator',
        'initial' => 'A',
    ];
}

function require_admin(): array
{
    $user = admin_user();
    if (!$user) {
        header('Location: /admin/login');
        exit;
    }
    return $user;
}

function attempt_admin_login(string $username, string $password): bool
{
    $creds = admin_credentials();
    $storedUser = (string) ($creds['username'] ?? '');
    $hash = (string) ($creds['password_hash'] ?? '');

    if ($storedUser === '' || $hash === '') {
        return false;
    }

    if (!hash_equals($storedUser, $username)) {
        return false;
    }

    if (!password_verify($password, $hash)) {
        return false;
    }

    admin_session_start();
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $storedUser;
    return true;
}

function admin_logout(): void
{
    admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }
    session_destroy();
}

function orders_all(): array
{
    $orders = load_json('orders.json');
    if (!is_array($orders)) {
        return [];
    }

    usort($orders, static function (array $a, array $b): int {
        return strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''));
    });

    return array_values($orders);
}

function order_status(array $order): string
{
    $status = (string) ($order['status'] ?? 'new');
    $allowed = ['new', 'seen', 'done', 'cancelled'];
    return in_array($status, $allowed, true) ? $status : 'new';
}

function order_status_label(string $status): string
{
    return match ($status) {
        'seen' => 'Ko‘rilgan',
        'done' => 'Bajarilgan',
        'cancelled' => 'Bekor',
        default => 'Yangi',
    };
}

function update_order_status(string $id, string $status): bool
{
    $allowed = ['new', 'seen', 'done', 'cancelled'];
    if (!in_array($status, $allowed, true)) {
        return false;
    }

    $orders = load_json('orders.json');
    if (!is_array($orders)) {
        return false;
    }

    $found = false;
    foreach ($orders as &$order) {
        if (($order['id'] ?? '') === $id) {
            $order['status'] = $status;
            $order['updated_at'] = date('c');
            $found = true;
            break;
        }
    }
    unset($order);

    return $found && save_json('orders.json', array_values($orders));
}

function delete_order(string $id): bool
{
    $orders = load_json('orders.json');
    if (!is_array($orders)) {
        return false;
    }

    $next = [];
    $found = false;
    foreach ($orders as $order) {
        if (($order['id'] ?? '') === $id) {
            $found = true;
            continue;
        }
        $next[] = $order;
    }

    return $found && save_json('orders.json', $next);
}

function orders_stats(array $orders): array
{
    $today = date('Y-m-d');
    $month = date('Y-m');
    $stats = [
        'total' => count($orders),
        'today' => 0,
        'month' => 0,
        'new' => 0,
        'seen' => 0,
        'done' => 0,
        'cancelled' => 0,
    ];

    foreach ($orders as $order) {
        $created = (string) ($order['created_at'] ?? '');
        $day = substr($created, 0, 10);
        $ym = substr($created, 0, 7);
        if ($day === $today) {
            $stats['today']++;
        }
        if ($ym === $month) {
            $stats['month']++;
        }
        $status = order_status($order);
        if (isset($stats[$status])) {
            $stats[$status]++;
        }
    }

    return $stats;
}

function format_admin_date(?string $iso): string
{
    if (!$iso) {
        return '—';
    }
    $ts = strtotime($iso);
    if ($ts === false) {
        return $iso;
    }
    return date('d.m.Y H:i', $ts);
}

function render_admin(string $view, array $vars = []): void
{
    $adminUser = $vars['adminUser'] ?? admin_user();
    $flash = $vars['flash'] ?? null;
    $activeNav = $vars['activeNav'] ?? $view;
    $pageTitle = $vars['pageTitle'] ?? 'Admin';
    $pageSub = $vars['pageSub'] ?? '';

    extract($vars, EXTR_SKIP);
    require ROOT_PATH . '/admin/views/layout.php';
}

function telegram_send_message(string $token, string $chatId, string $text): array
{
    $token = trim($token);
    $chatId = trim($chatId);

    if ($token === '' || $chatId === '') {
        return ['ok' => false, 'description' => 'Token yoki Chat ID bo‘sh.'];
    }

    $url = 'https://api.telegram.org/bot' . rawurlencode($token) . '/sendMessage';
    $payload = http_build_query([
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => '1',
    ]);

    $response = null;
    $error = null;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
        }
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 12,
                'ignore_errors' => true,
            ],
        ]);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            $error = 'Telegramga ulanib bo‘lmadi.';
        }
    }

    if ($error) {
        return ['ok' => false, 'description' => $error];
    }

    $data = json_decode((string) $response, true);
    if (!is_array($data)) {
        return ['ok' => false, 'description' => 'Telegramdan noto‘g‘ri javob.'];
    }

    return $data;
}

function format_order_telegram(array $order): string
{
    $service = category_label((string) ($order['service'] ?? ''));
    $lines = [
        '<b>🆕 Yangi buyurtma — Zaarqalam</b>',
        '',
        '👤 <b>Ism:</b> ' . e((string) ($order['name'] ?? '')),
        '📞 <b>Telefon:</b> ' . e((string) ($order['phone'] ?? '')),
        '🛠 <b>Xizmat:</b> ' . e($service),
    ];

    if (!empty($order['quantity'])) {
        $lines[] = '📦 <b>Miqdor:</b> ' . e((string) $order['quantity']);
    }
    if (!empty($order['message'])) {
        $lines[] = '💬 <b>Izoh:</b> ' . e((string) $order['message']);
    }

    $lines[] = '🕐 <b>Vaqt:</b> ' . e(format_admin_date($order['created_at'] ?? null));
    $lines[] = '🆔 <code>' . e((string) ($order['id'] ?? '')) . '</code>';

    return implode("\n", $lines);
}

function notify_telegram_order(array $order): array
{
    $settings = admin_settings();
    if (empty($settings['telegram_enabled'])) {
        return ['ok' => false, 'skipped' => true, 'description' => 'Telegram o‘chirilgan.'];
    }

    return telegram_send_message(
        (string) $settings['telegram_bot_token'],
        (string) $settings['telegram_chat_id'],
        format_order_telegram($order)
    );
}
