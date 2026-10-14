<?php
/** @var array $project */
/** @var array $relatedProjects */
?>
<section class="page-hero">
    <div class="container reveal">
        <p class="eyebrow"><?= e(category_label($project['category'] ?? '')) ?></p>
        <h1><?= e($project['title'] ?? '') ?></h1>
        <p><?= e($project['short'] ?? '') ?></p>
        <?php if (!empty($project['client'])): ?>
            <p style="margin-top:0.75rem;color:var(--black);font-weight:600;">Mijoz: <?= e($project['client']) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="detail-media reveal" style="margin-bottom:2rem;">
            <img src="<?= e(asset($project['image'] ?? '')) ?>" alt="<?= e($project['title'] ?? '') ?>" width="1200" height="900">
        </div>

        <div class="detail-grid">
            <div class="content-block reveal" style="margin-top:0;">
                <h2>Vazifa</h2>
                <p><?= e($project['task'] ?? '') ?></p>
                <h2 style="margin-top:2rem;">Yechim</h2>
                <p><?= e($project['solution'] ?? '') ?></p>
            </div>
            <div class="reveal">
                <div class="about-panel">
                    <h3>Loyiha ma’lumoti</h3>
                    <ul>
                        <li><strong>Kategoriya:</strong> <?= e(category_label($project['category'] ?? '')) ?></li>
                        <?php if (!empty($project['client'])): ?>
                            <li><strong>Mijoz:</strong> <?= e($project['client']) ?></li>
                        <?php endif; ?>
                    </ul>
                    <div style="margin-top:1.5rem;">
                        <a class="btn btn--primary" href="<?= e(page_url('/contact?service=' . urlencode($project['category'] ?? ''))) ?>">Shunga o‘xshash loyiha</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($project['gallery'])): ?>
            <div class="content-block reveal">
                <h2>Loyiha rasmlari</h2>
                <div class="gallery-grid">
                    <?php foreach ($project['gallery'] as $image): ?>
                        <img src="<?= e(asset($image)) ?>" alt="<?= e(($project['title'] ?? '') . ' — namuna') ?>" loading="lazy" width="1200" height="900">
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($relatedProjects): ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Yana</p>
                <h2 class="section-title">O‘xshash loyihalar</h2>
            </div>
        </div>
        <div class="project-grid">
            <?php foreach ($relatedProjects as $item): ?>
                <a class="project-card reveal" href="<?= e(page_url('/portfolio/' . ($item['slug'] ?? ''))) ?>">
                    <div class="project-card__image">
                        <img src="<?= e(asset($item['image'] ?? '')) ?>" alt="<?= e($item['title'] ?? '') ?>" loading="lazy" width="1200" height="900">
                    </div>
                    <div class="project-card__body">
                        <span class="meta-tag"><?= e(category_label($item['category'] ?? '')) ?></span>
                        <h3><?= e($item['title'] ?? '') ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
