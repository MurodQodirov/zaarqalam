<?php
/** @var array $site */
/** @var array $services */
/** @var array $projects */
$serviceIndex = 0;
?>
<section class="hero">
    <div class="container">
        <div class="hero-copy reveal">
            <p class="brand-mark">zaarqalam</p>
            <h1>
                <span class="hero-line">Sun‘iy intellekt</span>
                <span class="hero-line hero-line--accent">nafislik berolmaydi!</span>
            </h1>
            <p class="hero-text"><?= e($site['description'] ?? '') ?></p>
            <div class="hero-actions">
                <a class="btn btn--primary" href="#contact">
                    <span>Buyurtma berish</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
                <a class="btn btn--ghost" href="#portfolio">Ishlarimizni ko‘rish</a>
            </div>
        </div>
    </div>
</section>

<section class="section section--services" id="services">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Xizmatlar</p>
                <h2 class="section-title">Xizmatlar</h2>
            </div>
            <div class="section-head__side">
                <a class="link-arrow" href="<?= e(page_url('/services')) ?>">
                    Barcha xizmatlar
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <div class="service-stack">
            <?php foreach ($services as $service): ?>
                <?php $serviceIndex++; ?>
                <a class="service-row reveal" href="<?= e(page_url('/services/' . ($service['slug'] ?? ''))) ?>">
                    <span class="service-row__num"><?= str_pad((string) $serviceIndex, 2, '0', STR_PAD_LEFT) ?></span>
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

<section class="section section--portfolio" id="portfolio">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Ishlar</p>
                <h2 class="section-title">Portfolio</h2>
            </div>
        </div>

        <div class="filters reveal" role="tablist" aria-label="Portfolio filtrlari">
            <button type="button" class="filter-btn is-active" data-filter="all">Barchasi</button>
            <?php foreach ($services as $service): ?>
                <button type="button" class="filter-btn" data-filter="<?= e($service['slug'] ?? '') ?>"><?= e($service['title'] ?? '') ?></button>
            <?php endforeach; ?>
        </div>

        <div class="project-bento">
            <?php foreach ($projects as $i => $project): ?>
                <article class="project-tile reveal<?= $i === 0 ? ' project-tile--wide' : '' ?><?= $i === 1 ? ' project-tile--tall' : '' ?>"
                         data-category="<?= e($project['category'] ?? '') ?>">
                    <div class="project-tile__image">
                        <img src="<?= e(asset($project['image'] ?? '')) ?>" alt="<?= e($project['title'] ?? '') ?>" loading="lazy" width="1200" height="900">
                    </div>
                    <div class="project-tile__meta">
                        <span><?= e(category_label($project['category'] ?? '')) ?></span>
                        <h3><?= e($project['title'] ?? '') ?></h3>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--why" id="about">
    <div class="container">
        <div class="why-layout">
            <div class="why-intro reveal">
                <p class="eyebrow">Kompaniya</p>
                <h2 class="section-title">Biz haqimizda</h2>
                <p class="section-lead"><?= e($site['about']['lead'] ?? '') ?></p>
            </div>
            <div class="why-list">
                <?php foreach (($site['advantages'] ?? []) as $i => $item): ?>
                    <article class="why-item reveal">
                        <span class="why-item__index"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <div>
                            <h3><?= e($item['title'] ?? '') ?></h3>
                            <p><?= e($item['text'] ?? '') ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="section section--process">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Jarayon</p>
                <h2 class="section-title">Ish<br>jarayoni</h2>
            </div>
            <p class="section-lead">Murojaatdan topshirishgacha — aniq va tushunarli bosqichlar.</p>
        </div>

        <div class="process-rail">
            <?php foreach (($site['process'] ?? []) as $step): ?>
                <article class="process-step-card reveal">
                    <div class="process-step-card__num"><?= e($step['step'] ?? '') ?></div>
                    <h3><?= e($step['title'] ?? '') ?></h3>
                    <p><?= e($step['text'] ?? '') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" id="contact">
    <div class="container">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Aloqa</p>
                <h2 class="section-title">Murojat uchun</h2>
            </div>
        </div>
        <div class="contact-layout">
            <aside class="contact-info reveal">
                <?php if (!empty($site['phone'])): ?>
                    <div>
                        <div class="label">Telefon</div>
                        <a href="tel:<?= e($site['phone_raw'] ?? $site['phone']) ?>"><?= e($site['phone']) ?></a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($site['telegram'])): ?>
                    <div>
                        <div class="label">Telegram</div>
                        <a href="<?= e($site['telegram']) ?>" target="_blank" rel="noopener"><?= e($site['telegram_label'] ?? '@zarqalam') ?></a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($site['instagram'])): ?>
                    <div>
                        <div class="label">Instagram</div>
                        <a href="<?= e($site['instagram']) ?>" target="_blank" rel="noopener"><?= e($site['instagram_label'] ?? '@zarqalam') ?></a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($site['email'])): ?>
                    <div>
                        <div class="label">Email</div>
                        <a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a>
                    </div>
                <?php endif; ?>
                <?php if (!empty($site['address'])): ?>
                    <div>
                        <div class="label">Manzil</div>
                        <p><?= e($site['address']) ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($site['work_hours'])): ?>
                    <div>
                        <div class="label">Ish vaqti</div>
                        <p><?= e($site['work_hours']) ?></p>
                    </div>
                <?php endif; ?>
            </aside>
            <div class="form-card reveal">
                <form data-order-form enctype="multipart/form-data" novalidate>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="name">Ism *</label>
                            <input type="text" id="name" name="name" required autocomplete="name" placeholder="Ismingiz">
                            <span class="error" data-error="name"></span>
                        </div>
                        <div class="form-field">
                            <label for="phone">Telefon *</label>
                            <input type="tel" id="phone" name="phone" required autocomplete="tel" placeholder="+998 XX XXX XX XX">
                            <span class="error" data-error="phone"></span>
                        </div>
                        <div class="form-field">
                            <label for="service">Xizmat turi *</label>
                            <select id="service" name="service" required>
                                <option value="">Tanlang</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?= e($service['slug'] ?? '') ?>"><?= e($service['title'] ?? '') ?></option>
                                <?php endforeach; ?>
                                <option value="other">Boshqa</option>
                            </select>
                            <span class="error" data-error="service"></span>
                        </div>
                        <div class="form-field">
                            <label for="quantity">Miqdor (ixtiyoriy)</label>
                            <input type="text" id="quantity" name="quantity" placeholder="Masalan: 500 dona">
                            <span class="error" data-error="quantity"></span>
                        </div>
                        <div class="form-field form-field--full">
                            <label for="message">Izoh</label>
                            <textarea id="message" name="message" placeholder="Loyiha haqida qisqa yozing"></textarea>
                            <span class="error" data-error="message"></span>
                        </div>
                    </div>
                    <div style="margin-top:1.25rem;">
                        <button class="btn btn--primary" type="submit">Buyurtma yuborish</button>
                    </div>
                    <div class="form-status" data-form-status role="status" aria-live="polite"></div>
                </form>
            </div>
        </div>
    </div>
</section>
