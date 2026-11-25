<?php $escape = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Monitoring Santri</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
</head>
<body class="login-page">
    <main class="login-card">

        <p class="login-eyebrow">SISTEM MONITORING SANTRI</p>
        <h1>Selamat datang</h1>
        <p class="login-description">Masuk menggunakan akun admin</p>
        <?php if (!empty($data['error'])): ?>
            <p class="login-error" role="alert"><?= $escape($data['error']) ?></p>
        <?php endif; ?>
        <form method="post" action="<?= BASEURL ?>/Auth/login">
            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" autocomplete="username" value="<?= $escape($data['username'] ?? '') ?>" required autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <button class="login-submit" type="submit">Masuk</button>
        </form>
        <p class="login-note">Gunakan username dan password yang terdaftar pada akun Anda.</p>
    </main>
</body>
</html>
