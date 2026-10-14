</main>
<?php $site = $site ?? site_data(); $services = $services ?? services_data(); ?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="logo logo--footer" href="<?= e(page_url('/')) ?>" aria-label="zaarqalam">
                    <img src="<?= e(asset('public/logo/2.png')) ?>" alt="zaarqalam" width="1849" height="331" class="logo-img">
                </a>
                <p class="footer-about"><?= e($site['about_short'] ?? '') ?></p>
            </div>

            <div class="footer-col footer-col--services">
                <h4>Xizmatlar</h4>
                <?php foreach ($services as $service): ?>
                    <a href="<?= e(page_url('/services/' . ($service['slug'] ?? ''))) ?>"><?= e($service['title'] ?? '') ?></a>
                <?php endforeach; ?>
            </div>

            <div class="footer-col">
                <h4>Kompaniya</h4>
                <a href="<?= e(page_url('/portfolio')) ?>">Portfolio</a>
                <a href="<?= e(page_url('/about')) ?>">Biz haqimizda</a>
                <a href="<?= e(page_url('/contact')) ?>">Aloqa</a>
                <a href="<?= e(page_url('/privacy-policy')) ?>">Maxfiylik siyosati</a>
            </div>

            <div class="footer-col">
                <h4>Bog‘lanish</h4>
                <?php if (!empty($site['phone'])): ?>
                    <a href="tel:<?= e($site['phone_raw'] ?? $site['phone']) ?>"><?= e($site['phone']) ?></a>
                <?php endif; ?>
                <?php if (!empty($site['telegram'])): ?>
                    <a href="<?= e($site['telegram']) ?>" target="_blank" rel="noopener">Telegram: <?= e($site['telegram_label'] ?? 'Zarqalam') ?></a>
                <?php endif; ?>
                <?php if (!empty($site['instagram'])): ?>
                    <a href="<?= e($site['instagram']) ?>" target="_blank" rel="noopener">Instagram: <?= e($site['instagram_label'] ?? 'Zarqalam') ?></a>
                <?php endif; ?>
                <?php if (!empty($site['address'])): ?>
                    <p><?= e($site['address']) ?></p>
                <?php endif; ?>
                <?php if (!empty($site['work_hours'])): ?>
                    <p><?= e($site['work_hours']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© <?= date('Y') ?> Zarqalam. Barcha huquqlar himoyalangan.</span>
            <a href="<?= e(page_url('/privacy-policy')) ?>">Maxfiylik siyosati</a>
        </div>
    </div>
</footer>
<script src="<?= e(asset('public/js/main.js')) ?>" defer></script>
</body>
</html>
