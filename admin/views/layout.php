<?php
/** @var array $adminUser */
/** @var string $pageTitle */
/** @var string $pageSub */
/** @var string $activeNav */
/** @var array|null $flash */
$todayLabel = date('d.m.Y');
?>
<!doctype html>
<html lang="uz">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#f7f9fc">
  <title><?= e($pageTitle) ?> — Zaarqalam Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/admin.css">
</head>
<body class="app">
  <aside class="sidebar">
    <div class="brand"><b>Zaarqalam</b></div>
    <p class="nav-label">Asosiy menyu</p>
    <nav>
      <a href="/admin" class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="/admin/orders" class="<?= $activeNav === 'orders' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        <span>Buyurtmalar</span>
      </a>
    </nav>
    <p class="nav-label">Ma’lumotlar</p>
    <nav>
      <a href="/admin/settings" class="<?= $activeNav === 'settings' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M18.4 5.6 17 7M7 17l-1.4 1.4"/></svg>
        <span>Sozlamalar</span>
      </a>
    </nav>
    <div class="side-foot">
      <div class="account">
        <span class="mark"><?= e($adminUser['initial'] ?? 'A') ?><i></i></span>
        <div>
          <b><?= e($adminUser['name'] ?? 'Admin') ?></b>
          <span><?= e($adminUser['username'] ?? 'admin') ?></span>
        </div>
      </div>
      <a class="logout" href="/admin/logout">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 6H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h3"/><path d="M15 16l4-4-4-4"/><path d="M19 12H9"/></svg>
        Chiqish
      </a>
    </div>
  </aside>

  <header class="m-head">
    <b>Zaarqalam</b>
    <a class="m-out" href="/admin/logout" aria-label="Chiqish">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 6H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h3"/><path d="M15 16l4-4-4-4"/><path d="M19 12H9"/></svg>
    </a>
  </header>
  <nav class="tabbar">
    <a href="/admin" class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      <span>Dashboard</span>
    </a>
    <a href="/admin/orders" class="<?= $activeNav === 'orders' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
      <span>Buyurtmalar</span>
    </a>
    <a href="/admin/settings" class="<?= $activeNav === 'settings' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2"/></svg>
      <span>Sozlamalar</span>
    </a>
  </nav>

  <main class="content">
    <header class="topbar">
      <div>
        <h1><?= e($pageTitle) ?></h1>
        <?php if ($pageSub !== ''): ?><p class="sub"><?= e($pageSub) ?></p><?php endif; ?>
      </div>
      <div class="date-chip">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
        <?= e($todayLabel) ?>
      </div>
    </header>

    <?php if (!empty($flash)): ?>
      <div class="flash <?= e($flash['type'] ?? 'ok') ?>"><?= e($flash['text'] ?? '') ?></div>
    <?php endif; ?>

    <?php require ROOT_PATH . '/admin/views/' . $view . '.php'; ?>
  </main>

  <script>
    document.querySelectorAll(".table-wrap table").forEach(function (table) {
      var heads = Array.from(table.querySelectorAll("thead th")).map(function (th) { return th.textContent.trim(); });
      table.querySelectorAll("tbody tr").forEach(function (tr) {
        Array.from(tr.children).forEach(function (td, i) {
          if (!td.hasAttribute("colspan")) td.dataset.label = heads[i] || "";
        });
      });
    });
  </script>
</body>
</html>
