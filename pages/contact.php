<?php
/** @var array $site */
/** @var array $services */
/** @var string $preselectService */
$preselectService = $preselectService ?? '';
?>
<section class="page-hero">
    <div class="container reveal">
        <p class="eyebrow">Aloqa</p>
        <h1>Murojat uchun</h1>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container contact-layout">
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
                                <option value="<?= e($service['slug'] ?? '') ?>" <?= ($preselectService === ($service['slug'] ?? '')) ? 'selected' : '' ?>>
                                    <?= e($service['title'] ?? '') ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" <?= $preselectService === 'other' ? 'selected' : '' ?>>Boshqa</option>
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
</section>
