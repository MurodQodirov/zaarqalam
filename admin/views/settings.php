<?php
/** @var array $settings */
$enabled = !empty($settings['telegram_enabled']);
$tokenSet = trim((string) ($settings['telegram_bot_token'] ?? '')) !== '';
$chatSet = trim((string) ($settings['telegram_chat_id'] ?? '')) !== '';
?>
<section class="card form">
  <div class="card-head" style="padding:0 0 12px">
    <h2>Telegram bot</h2>
    <span class="bot-status <?= $enabled && $tokenSet && $chatSet ? 'on' : 'off' ?>">
      <i class="dot"></i>
      <?= $enabled && $tokenSet && $chatSet ? 'Faol' : 'Nofaol' ?>
    </span>
  </div>
  <p class="sub" style="margin:0 0 16px">Website orqali kelgan buyurtmalar shu bot orqali Telegramga avtomatik yuboriladi.</p>
  <form method="post">
    <input type="hidden" name="action" value="telegram">
    <label>Bot token
      <input type="text" name="telegram_bot_token" value="<?= e($settings['telegram_bot_token'] ?? '') ?>" placeholder="123456:ABC-DEF..." autocomplete="off">
    </label>
    <label>Chat ID
      <input type="text" name="telegram_chat_id" value="<?= e($settings['telegram_chat_id'] ?? '') ?>" placeholder="-100... yoki shaxsiy chat id" autocomplete="off">
    </label>
    <label class="check">
      <input type="checkbox" name="telegram_enabled" value="1" <?= $enabled ? 'checked' : '' ?>>
      Buyurtmalarni Telegramga avtomatik yuborish
    </label>
    <div class="modal-actions" style="justify-content:flex-start;margin-top:16px">
      <button type="submit">Saqlash</button>
    </div>
  </form>
  <form method="post" style="margin-top:12px">
    <input type="hidden" name="action" value="test">
    <button class="ghost" type="submit">Test xabar yuborish</button>
  </form>
</section>

<section class="card form">
  <div class="card-head" style="padding:0 0 12px">
    <h2>Admin parol</h2>
  </div>
  <form method="post">
    <input type="hidden" name="action" value="password">
    <label>Joriy parol
      <input type="password" name="current_password" required autocomplete="current-password">
    </label>
    <div class="grid2">
      <label>Yangi parol
        <input type="password" name="new_password" minlength="6" required autocomplete="new-password">
      </label>
      <label>Yangi parol (takror)
        <input type="password" name="confirm_password" minlength="6" required autocomplete="new-password">
      </label>
    </div>
    <button type="submit">Parolni yangilash</button>
  </form>
</section>
