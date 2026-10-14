<?php
/** @var array $service */
/** @var array $services */
/** @var array $relatedProjects */
?>
<section class="page-hero">
    <div class="container reveal">
        <p class="eyebrow">Xizmat</p>
        <h1><?= e($service['title'] ?? '') ?></h1>
        <p><?= e($service['description'] ?? $service['short'] ?? '') ?></p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container detail-grid">
        <div class="detail-media reveal">
            <img src="<?= e(asset($service['image'] ?? '')) ?>" alt="<?= e($service['title'] ?? '') ?>" width="800" height="600">
        </div>
        <div class="reveal">
            <div class="content-block" style="margin-top:0;">
                <h2>Xizmat tarkibi</h2>
                <ul class="includes-list">
                    <?php foreach (($service['includes'] ?? []) as $item): ?>
                        <li><?= e($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div style="margin-top: 1.75rem;">
                <a class="btn btn--primary" href="<?= e(page_url('/contact?service=' . urlencode($service['slug'] ?? ''))) ?>">Buyurtma berish</a>
            </div>
        </div>
    </div>
</section>

<?php if ($relatedProjects): ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Namunalar</p>
                <h2 class="section-title">Tegishli ishlar</h2>
            </div>
            <a class="link-arrow" href="<?= e(page_url('/portfolio')) ?>">
                Portfolio
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="project-grid">
            <?php foreach ($relatedProjects as $project): ?>
                <a class="project-card reveal" href="<?= e(page_url('/portfolio/' . ($project['slug'] ?? ''))) ?>">
                    <div class="project-card__image">
                        <img src="<?= e(asset($project['image'] ?? '')) ?>" alt="<?= e($project['title'] ?? '') ?>" loading="lazy" width="1200" height="900">
                    </div>
                    <div class="project-card__body">
                        <span class="meta-tag"><?= e(category_label($project['category'] ?? '')) ?></span>
                        <h3><?= e($project['title'] ?? '') ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($service['faq'])): ?>
<section class="section">
    <div class="container" style="max-width: 820px;">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">FAQ</p>
                <h2 class="section-title">Ko‘p so‘raladigan savollar</h2>
            </div>
        </div>
        <div class="faq-list">
            <?php foreach ($service['faq'] as $faq): ?>
                <details class="faq-item reveal">
                    <summary><?= e($faq['q'] ?? '') ?></summary>
                    <p><?= e($faq['a'] ?? '') ?></p>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--alt">
    <div class="container reveal">
        <p class="eyebrow">Boshqa xizmatlar</p>
        <h2 class="section-title" style="margin-bottom: 1.5rem;">Yana nimalar taklif qilamiz</h2>
        <div class="related-services">
            <?php foreach ($services as $item): ?>
                <?php if (($item['slug'] ?? '') === ($service['slug'] ?? '')) continue; ?>
                <a class="chip" href="<?= e(page_url('/services/' . ($item['slug'] ?? ''))) ?>"><?= e($item['title'] ?? '') ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
