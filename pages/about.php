<?php
/** @var array $site */
?>
<section class="page-hero">
    <div class="container reveal">
        <p class="eyebrow">Kompaniya</p>
        <h1><?= e($site['about']['title'] ?? 'Biz haqimizda') ?></h1>
        <p><?= e($site['about']['lead'] ?? '') ?></p>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container about-grid">
        <div class="about-copy reveal">
            <?php foreach (($site['about']['text'] ?? []) as $paragraph): ?>
                <p><?= e($paragraph) ?></p>
            <?php endforeach; ?>
        </div>
        <aside class="about-panel reveal">
            <h3>Bizning yondashuv</h3>
            <ul>
                <?php foreach (($site['advantages'] ?? []) as $item): ?>
                    <li>
                        <strong><?= e($item['title'] ?? '') ?></strong><br>
                        <span style="color:var(--gray);font-size:0.95rem;"><?= e($item['text'] ?? '') ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Jarayon</p>
                <h2 class="section-title">Qanday ishlaymiz</h2>
            </div>
        </div>
        <div class="process-grid">
            <?php foreach (($site['process'] ?? []) as $step): ?>
                <article class="process-card reveal">
                    <div class="process-step"><?= e($step['step'] ?? '') ?></div>
                    <h3><?= e($step['title'] ?? '') ?></h3>
                    <p><?= e($step['text'] ?? '') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="container cta-banner__inner reveal">
        <p class="eyebrow eyebrow--light">Aloqa</p>
        <h2>Loyihangizni<br>birgalikda<br>boshlaymiz.</h2>
        <p>Zarqalam bilan bog‘laning — vazifangizga mos yechimni taklif qilamiz.</p>
        <a class="btn btn--light" href="<?= e(page_url('/contact')) ?>">
            <span>Bog‘lanish</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>
