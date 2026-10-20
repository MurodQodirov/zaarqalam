<?php
/** @var array $orders */
/** @var array $stats */
/** @var string $filter */
/** @var string $q */
$tabs = [
    'all' => ['label' => 'Barchasi', 'count' => (int) ($stats['total'] ?? 0)],
    'new' => ['label' => 'Yangi', 'count' => (int) ($stats['new'] ?? 0)],
    'seen' => ['label' => 'Ko‘rilgan', 'count' => (int) ($stats['seen'] ?? 0)],
    'done' => ['label' => 'Bajarilgan', 'count' => (int) ($stats['done'] ?? 0)],
    'cancelled' => ['label' => 'Bekor', 'count' => (int) ($stats['cancelled'] ?? 0)],
];
?>
<section class="card orders-panel">
  <div class="orders-toolbar">
    <div class="status-tabs">
      <?php foreach ($tabs as $key => $tab): ?>
        <a class="status-tab <?= $filter === $key ? 'on' : '' ?>"
           href="/admin/orders?status=<?= e($key) ?><?= $q !== '' ? '&q=' . urlencode($q) : '' ?>">
          <?= e($tab['label']) ?>
          <em><?= (int) $tab['count'] ?></em>
        </a>
      <?php endforeach; ?>
    </div>
    <form class="orders-search" method="get" action="/admin/orders">
      <input type="hidden" name="status" value="<?= e($filter) ?>">
      <input type="search" name="q" value="<?= e($q) ?>" placeholder="Ism, telefon yoki izoh…">
      <button type="submit">Qidirish</button>
    </form>
  </div>

  <?php if (!$orders): ?>
    <div class="orders-empty">
      <strong>Buyurtma yo‘q</strong>
      <p>Bu filtr bo‘yicha hali hech narsa topilmadi.</p>
    </div>
  <?php else: ?>
    <div class="order-list">
      <?php foreach ($orders as $order): ?>
        <?php
          $status = order_status($order);
          $id = (string) ($order['id'] ?? '');
          $phone = (string) ($order['phone'] ?? '');
          $phoneHref = preg_replace('/\s+/', '', $phone) ?? '';
          $qty = trim((string) ($order['quantity'] ?? ''));
          $message = trim((string) ($order['message'] ?? ''));
        ?>
        <article class="order-item">
          <div class="order-main">
            <div class="order-top">
              <h3><?= e($order['name'] ?? '') ?></h3>
              <span class="order-time"><?= e(format_admin_date($order['created_at'] ?? null)) ?></span>
            </div>
            <div class="order-facts">
              <a class="fact" href="tel:<?= e($phoneHref) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.5-1.1a2 2 0 0 1 2.1-.4c.9.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2.1z"/></svg>
                <?= e($phone !== '' ? $phone : '—') ?>
              </a>
              <span class="fact">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                <?= e(category_label((string) ($order['service'] ?? ''))) ?>
              </span>
              <?php if ($qty !== ''): ?>
                <span class="fact">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.7z"/></svg>
                  <?= e($qty) ?>
                </span>
              <?php endif; ?>
            </div>
            <?php if ($message !== ''): ?>
              <p class="order-note"><?= e($message) ?></p>
            <?php endif; ?>
          </div>
          <div class="order-side">
            <form method="post" class="status-form">
              <input type="hidden" name="action" value="status">
              <input type="hidden" name="id" value="<?= e($id) ?>">
              <label class="side-label">Holat</label>
              <select class="status <?= e($status) ?>" name="status" onchange="this.form.submit()">
                <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>Yangi</option>
                <option value="seen" <?= $status === 'seen' ? 'selected' : '' ?>>Ko‘rilgan</option>
                <option value="done" <?= $status === 'done' ? 'selected' : '' ?>>Bajarilgan</option>
                <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Bekor</option>
              </select>
            </form>
            <form method="post" onsubmit="return confirm('Buyurtmani o‘chirasizmi?');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= e($id) ?>">
              <button class="btn-soft danger" type="submit">O‘chirish</button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
