<?php
/** @var array $projects */
?>
<section class="page-hero">
    <div class="container reveal">
        <p class="eyebrow">Portfolio</p>
        <h1>Barcha<br>ishlar</h1>
        <p>Brending, qadoq, sovg‘a naborlari va poligrafiya yo‘nalishidagi tanlangan loyihalar.</p>
    </div>
</section>

<section class="section section--portfolio" style="padding-top: 0;">
    <div class="container">
        <div class="filters reveal" role="tablist" aria-label="Portfolio filtrlari">
            <button type="button" class="filter-btn is-active" data-filter="all">Barchasi</button>
            <?php foreach (($services ?? services_data()) as $service): ?>
                <button type="button" class="filter-btn" data-filter="<?= e($service['slug'] ?? '') ?>"><?= e($service['title'] ?? '') ?></button>
            <?php endforeach; ?>
        </div>

        <div class="project-bento">
            <?php foreach ($projects as $i => $project): ?>
                <a class="project-tile reveal<?= $i % 5 === 0 ? ' project-tile--wide' : '' ?>"
                   href="<?= e(page_url('/portfolio/' . ($project['slug'] ?? ''))) ?>"
                   data-category="<?= e($project['category'] ?? '') ?>">
                    <div class="project-tile__image">
                        <img src="<?= e(asset($project['image'] ?? '')) ?>" alt="<?= e($project['title'] ?? '') ?>" loading="lazy" width="1200" height="900">
                    </div>
                    <div class="project-tile__meta">
                        <span><?= e(category_label($project['category'] ?? '')) ?></span>
                        <h3><?= e($project['title'] ?? '') ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
