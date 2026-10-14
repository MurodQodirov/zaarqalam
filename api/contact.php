<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Faqat POST so‘rovi qabul qilinadi.'], 405);
}

$validation = validate_order($_POST);
if ($validation['errors']) {
    json_response([
        'success' => false,
        'message' => 'Formani to‘g‘ri to‘ldiring.',
        'errors' => $validation['errors'],
    ], 422);
}

$data = $validation['data'];
$attachment = null;

if (!empty($_FILES['file']) && is_array($_FILES['file']) && ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['file'];

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        json_response([
            'success' => false,
            'message' => 'Fayl yuklashda xatolik.',
            'errors' => ['file' => 'Faylni qayta yuklab ko‘ring.'],
        ], 422);
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if (($file['size'] ?? 0) > $maxSize) {
        json_response([
            'success' => false,
            'message' => 'Fayl hajmi katta.',
            'errors' => ['file' => 'Fayl 5 MB dan oshmasin.'],
        ], 422);
    }

    $originalName = (string) ($file['name'] ?? 'file');
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'zip', 'ai', 'psd'];

    if (!in_array($ext, $allowed, true)) {
        json_response([
            'success' => false,
            'message' => 'Fayl formati qo‘llab-quvvatlanmaydi.',
            'errors' => ['file' => 'Ruxsat etilgan: pdf, jpg, png, webp, zip, ai, psd.'],
        ], 422);
    }

    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0755, true);
    }

    $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($originalName, PATHINFO_FILENAME)) ?: 'file';
    $filename = date('Ymd-His') . '-' . $safeBase . '.' . $ext;
    $destination = UPLOAD_PATH . '/' . $filename;

    if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
        json_response([
            'success' => false,
            'message' => 'Fayl saqlanmadi.',
            'errors' => ['file' => 'Faylni saqlab bo‘lmadi.'],
        ], 500);
    }

    $attachment = [
        'original_name' => $originalName,
        'stored_name' => $filename,
        'path' => '/public/uploads/' . $filename,
        'size' => (int) ($file['size'] ?? 0),
    ];
}

$orders = load_json('orders.json');
if (!is_array($orders)) {
    $orders = [];
}

$order = [
    'id' => uniqid('ord_', true),
    'created_at' => date('c'),
    'name' => $data['name'],
    'phone' => $data['phone'],
    'service' => $data['service'],
    'quantity' => $data['quantity'],
    'message' => $data['message'],
    'attachment' => $attachment,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
    'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
];

$orders[] = $order;

if (!save_json('orders.json', $orders)) {
    // Rollback uploaded file if order save fails
    if ($attachment && is_file(UPLOAD_PATH . '/' . $attachment['stored_name'])) {
        unlink(UPLOAD_PATH . '/' . $attachment['stored_name']);
    }

    json_response([
        'success' => false,
        'message' => 'Buyurtmani saqlab bo‘lmadi. Keyinroq urinib ko‘ring.',
    ], 500);
}

json_response([
    'success' => true,
    'message' => 'Buyurtmangiz muvaffaqiyatli qabul qilindi. Tez orada bog‘lanamiz.',
    'id' => $order['id'],
]);
