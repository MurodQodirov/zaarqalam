<?php
/** @var array $stats */
/** @var array $settings */
/** @var array $visits */
$visits = $visits ?? [];
$series = $visits['series'] ?? [];
$max = max(1, (int) ($visits['max'] ?? 1));
$tgOn = !empty($settings['telegram_enabled']) && ($settings['telegram_bot_token'] ?? '') !== '' && ($settings['telegram_chat_id'] ?? '') !== '';
?>
<div class="kpi-row">
  <div class="kpi">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
    <div><span>Bugun tashrif buyuruvchilar</span><strong><?= (int) ($visits['today_visitors'] ?? 0) ?></strong></div>
  </div>
  <div class="kpi">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div>
    <div><span>Bugun ko‘rishlar</span><strong><?= (int) ($visits['today_views'] ?? 0) ?></strong></div>
  </div>
  <div class="kpi">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/></svg></div>
    <div><span>Shu oy tashriflar</span><strong><?= (int) ($visits['month_visitors'] ?? 0) ?></strong></div>
  </div>
</div>

<section class="card chart-card">
  <div class="card-head">
    <h2>Kunlik tashriflar · 14 kun</h2>
  </div>
  <div class="legend">
    <span><i class="swatch in"></i>Tashrif buyuruvchilar</span>
    <span><i class="swatch out"></i>Ko‘rishlar</span>
  </div>
  <div class="visit-chart">
    <?php foreach ($series as $row): ?>
      <?php
        $vh = (int) round(((int) ($row['visitors'] ?? 0) / $max) * 100);
        $ph = (int) round(((int) ($row['views'] ?? 0) / $max) * 100);
      ?>
      <div class="visit-col" title="<?= e(($row['date'] ?? '') . ': ' . (int) ($row['visitors'] ?? 0) . ' tashrif, ' . (int) ($row['views'] ?? 0) . ' ko‘rish') ?>">
        <div class="visit-bars">
          <div class="visit-bar in" style="height: <?= max($vh, ($row['visitors'] ?? 0) > 0 ? 6 : 0) ?>%"></div>
          <div class="visit-bar out" style="height: <?= max($ph, ($row['views'] ?? 0) > 0 ? 6 : 0) ?>%"></div>
        </div>
        <span><?= e($row['label'] ?? '') ?></span>
        <small><?= (int) ($row['visitors'] ?? 0) ?></small>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="visit-meta">
    <div><span>Kecha</span><b><?= (int) ($visits['yesterday_visitors'] ?? 0) ?> / <?= (int) ($visits['yesterday_views'] ?? 0) ?></b></div>
    <div><span>14 kun jami</span><b><?= (int) ($visits['period_visitors'] ?? 0) ?> / <?= (int) ($visits['period_views'] ?? 0) ?></b></div>
    <div><span>Shu oy ko‘rishlar</span><b><?= (int) ($visits['month_views'] ?? 0) ?></b></div>
  </div>
</section>

<div class="kpi-row">
  <a class="kpi" href="/admin/orders">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
    <div><span>Jami buyurtmalar</span><strong><?= (int) ($stats['total'] ?? 0) ?></strong></div>
  </a>
  <a class="kpi" href="/admin/orders?status=new">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg></div>
    <div><span>Yangi buyurtmalar</span><strong class="<?= !empty($stats['new']) ? 'neg' : '' ?>"><?= (int) ($stats['new'] ?? 0) ?></strong></div>
  </a>
  <a class="kpi" href="/admin/orders">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
    <div><span>Bugungi buyurtmalar</span><strong><?= (int) ($stats['today'] ?? 0) ?></strong></div>
  </a>
</div>

<div class="kpi-row kpi-tasks">
  <div class="kpi">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/></svg></div>
    <div><span>Shu oy buyurtma</span><strong><?= (int) ($stats['month'] ?? 0) ?></strong></div>
  </div>
  <div class="kpi">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
    <div><span>Bajarilgan</span><strong><?= (int) ($stats['done'] ?? 0) ?></strong></div>
  </div>
  <div class="kpi">
    <div class="kpi-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
    <div>
      <span>Telegram</span>
      <strong style="font-size:18px"><?= $tgOn ? 'Yoqilgan' : 'O‘chirilgan' ?></strong>
    </div>
  </div>
</div>
