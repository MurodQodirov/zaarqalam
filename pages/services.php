<?php
/** @var array $services */
$i = 0;
?>
<section class="page-hero">
    <div class="container reveal">
        <p class="eyebrow">Xizmatlar</p>
        <h1>Brendingdan<br>poligrafiyagacha</h1>
        <p>Zarqalam logotip, qadoq, korporativ sovg‘alar va bosma materiallar uchun amaliy dizayn yechimlarini taklif qiladi.</p>
    </div>
</section>

<section class="section section--services" style="padding-top: 0; background: transparent;">
    <div class="container">
        <div class="service-stack">
            <?php foreach ($services as $service): ?>
                <?php $i++; ?>
                <a class="service-row reveal" href="<?= e(page_url('/services/' . ($service['slug'] ?? ''))) ?>">
                    <span class="service-row__num"><?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?></span>
                    <div class="service-row__media">
                        <img src="<?= e(asset($service['image'] ?? '')) ?>" alt="" loading="lazy" width="800" height="600">
                    </div>
                    <div class="service-row__body">
                        <h3><?= e($service['title'] ?? '') ?></h3>
                        <p><?= e($service['short'] ?? '') ?></p>
                    </div>
                    <span class="service-row__go" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-banner__inner reveal">
        <p class="eyebrow eyebrow--light">Aloqa</p>
        <h2>Qaysi xizmat<br>kerak?</h2>
        <p>Loyihangizni qisqa aytib yuboring — mos yo‘nalishni birgalikda aniqlaymiz.</p>
        <a class="btn btn--light" href="<?= e(page_url('/contact')) ?>">
            <span>Bog‘lanish</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>
