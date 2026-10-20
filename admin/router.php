<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/admin.php';

admin_session_start();

$path = current_path();
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

if ($path === '/admin/login') {
    if (admin_user()) {
        header('Location: /admin');
        exit;
    }

    $error = '';
    if ($method === 'POST') {
        $username = trim((string) ($_POST['login'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if (attempt_admin_login($username, $password)) {
            header('Location: /admin');
            exit;
        }
        $error = 'Login yoki parol noto‘g‘ri.';
    }

    $company = 'Zaarqalam';
    require ROOT_PATH . '/admin/views/login.php';
    exit;
}

if ($path === '/admin/logout') {
    admin_logout();
    header('Location: /admin/login');
    exit;
}

$adminUser = require_admin();

if ($path === '/admin' || $path === '/admin/dashboard') {
    require_once ROOT_PATH . '/includes/analytics.php';
    $orders = orders_all();
    $stats = orders_stats($orders);
    $visits = visits_summary(14);
    render_admin('dashboard', [
        'adminUser' => $adminUser,
        'pageTitle' => 'Dashboard',
        'pageSub' => 'Buyurtmalar va tashriflar statistikasi',
        'activeNav' => 'dashboard',
        'stats' => $stats,
        'visits' => $visits,
        'settings' => admin_settings(),
    ]);
    exit;
}

if ($path === '/admin/orders') {
    if ($method === 'POST') {
        $action = (string) ($_POST['action'] ?? '');
        $id = (string) ($_POST['id'] ?? '');
        if ($action === 'status' && $id !== '') {
            update_order_status($id, (string) ($_POST['status'] ?? 'new'));
        }
        if ($action === 'delete' && $id !== '') {
            delete_order($id);
        }
        header('Location: /admin/orders');
        exit;
    }

    $orders = orders_all();
    $filter = (string) ($_GET['status'] ?? 'all');
    if ($filter !== 'all') {
        $orders = array_values(array_filter(
            $orders,
            static fn(array $o): bool => order_status($o) === $filter
        ));
    }

    $q = trim((string) ($_GET['q'] ?? ''));
    if ($q !== '') {
        $needle = mb_strtolower($q);
        $orders = array_values(array_filter($orders, static function (array $o) use ($needle): bool {
            $hay = mb_strtolower(
                ($o['name'] ?? '') . ' ' . ($o['phone'] ?? '') . ' ' . ($o['service'] ?? '') . ' ' . ($o['message'] ?? '')
            );
            return str_contains($hay, $needle);
        }));
    }

    render_admin('orders', [
        'adminUser' => $adminUser,
        'pageTitle' => 'Buyurtmalar',
        'pageSub' => 'Saytdan kelgan barcha buyurtmalar',
        'activeNav' => 'orders',
        'orders' => $orders,
        'filter' => $filter,
        'q' => $q,
        'stats' => orders_stats(orders_all()),
    ]);
    exit;
}

if ($path === '/admin/settings') {
    $flash = null;
    $settings = admin_settings();

    if ($method === 'POST') {
        $action = (string) ($_POST['action'] ?? 'telegram');

        if ($action === 'telegram') {
            $next = [
                'telegram_bot_token' => trim((string) ($_POST['telegram_bot_token'] ?? '')),
                'telegram_chat_id' => trim((string) ($_POST['telegram_chat_id'] ?? '')),
                'telegram_enabled' => isset($_POST['telegram_enabled']),
            ];
            if (save_admin_settings($next)) {
                $flash = ['type' => 'ok', 'text' => 'Telegram sozlamalari saqlandi.'];
                $settings = admin_settings();
            } else {
                $flash = ['type' => 'error', 'text' => 'Sozlamalarni saqlab bo‘lmadi.'];
            }
        }

        if ($action === 'test') {
            $settings = admin_settings();
            $result = telegram_send_message(
                (string) $settings['telegram_bot_token'],
                (string) $settings['telegram_chat_id'],
                "✅ <b>Zaarqalam</b>\nTest xabar muvaffaqiyatli yuborildi."
            );
            if (!empty($result['ok'])) {
                $flash = ['type' => 'ok', 'text' => 'Test xabar Telegramga yuborildi.'];
            } else {
                $flash = ['type' => 'error', 'text' => 'Yuborilmadi: ' . ($result['description'] ?? 'noma’lum xato')];
            }
        }

        if ($action === 'password') {
            $current = (string) ($_POST['current_password'] ?? '');
            $new = (string) ($_POST['new_password'] ?? '');
            $confirm = (string) ($_POST['confirm_password'] ?? '');
            $creds = admin_credentials();
            $hash = (string) ($creds['password_hash'] ?? '');

            if (!password_verify($current, $hash)) {
                $flash = ['type' => 'error', 'text' => 'Joriy parol noto‘g‘ri.'];
            } elseif (mb_strlen($new) < 6) {
                $flash = ['type' => 'error', 'text' => 'Yangi parol kamida 6 belgidan iborat bo‘lsin.'];
            } elseif ($new !== $confirm) {
                $flash = ['type' => 'error', 'text' => 'Yangi parollar mos kelmadi.'];
            } else {
                $creds['password_hash'] = password_hash($new, PASSWORD_DEFAULT);
                if (save_json('admin.json', $creds)) {
                    $flash = ['type' => 'ok', 'text' => 'Parol yangilandi.'];
                } else {
                    $flash = ['type' => 'error', 'text' => 'Parolni saqlab bo‘lmadi.'];
                }
            }
        }
    }

    render_admin('settings', [
        'adminUser' => $adminUser,
        'pageTitle' => 'Sozlamalar',
        'pageSub' => 'Telegram bot va admin parol',
        'activeNav' => 'settings',
        'settings' => $settings,
        'flash' => $flash,
    ]);
    exit;
}

http_response_code(404);
require_once ROOT_PATH . '/includes/analytics.php';
render_admin('dashboard', [
    'adminUser' => $adminUser,
    'pageTitle' => 'Topilmadi',
    'pageSub' => 'Sahifa mavjud emas',
    'activeNav' => 'dashboard',
    'stats' => orders_stats([]),
    'visits' => visits_summary(14),
    'settings' => admin_settings(),
    'flash' => ['type' => 'error', 'text' => 'Admin sahifa topilmadi.'],
]);
