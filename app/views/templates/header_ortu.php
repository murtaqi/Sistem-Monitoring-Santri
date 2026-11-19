<?php
$sidebarMenus = [
    'absensi' => ['label' => 'Absensi Anak', 'key' => 'absensi'],
    'hafalan' => ['label' => 'Hafalan Anak', 'key' => 'hafalan'],
    'pelanggaran' => ['label' => 'Pelanggaran Anak', 'key' => 'pelanggaran'],
];
$currentUser = $_SESSION['user'] ?? '';
$userName = htmlspecialchars($currentUser ?? 'Pengguna');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['judul'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?> | Monitoring Santri</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar" aria-label="Menu admin">
            <div class="brand">
                <div><strong>Monitoring Santri</strong><small>Panel Administrasi</small></div>
            </div>
            <p class="nav-label">MASTER DATA</p>
            <nav aria-label="Navigasi utama">
                <?php foreach ($sidebarMenus as $sidebarController => $sidebarMenu): ?>
                <a class="nav-item" href="<?= htmlspecialchars($sidebarController) ?>"<?= array_key_exists($sidebarMenu['key'], $data) ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M21 21v-3a6 6 0 0 0-4-5.65"/></svg>
                    <?= htmlspecialchars($sidebarMenu['label'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                <?php endforeach; ?>
            </nav>
        </aside>
        <div class="workspace">
            <header class="topbar">
                <span class="topbar-label">Sistem Monitoring Santri</span>
                <div class="profile"><span class="avatar" aria-hidden="true"><?= htmlspecialchars(strtoupper(substr($currentUser ?? 'U', 0, 1)), ENT_QUOTES, 'UTF-8') ?></span><?= $userName ?><a class="logout-link" href="<?= BASEURL ?>/Auth/logout">Keluar</a></div>
            </header>
            <main id="data-admin">