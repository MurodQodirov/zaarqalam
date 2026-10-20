<?php
/** @var string $error */
/** @var string $company */
?>
<!doctype html>
<html lang="uz">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#f7f9fc">
  <title>Kirish — Zaarqalam Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/admin.css">
</head>
<body class="auth">
  <div class="login-wrap">
    <form class="login-card" method="post" action="/admin/login">
      <div class="brand login-brand"><b><?= e($company) ?></b></div>
      <p>Admin panelga kirish</p>
      <?php if ($error !== ''): ?>
        <div class="flash error"><?= e($error) ?></div>
      <?php endif; ?>
      <label>Login</label>
      <input type="text" name="login" autocomplete="username" required autofocus>
      <label>Parol</label>
      <input type="password" name="password" minlength="3" autocomplete="current-password" required>
      <button type="submit">Kirish</button>
    </form>
  </div>
</body>
</html>
