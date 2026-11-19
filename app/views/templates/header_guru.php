<?php
$currentUser = $_SESSION['user'] ?? '';
$userName = htmlspecialchars($currentUser ?? 'Pengguna');
$currentPage = $data['judul'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['judul'] ?? 'Guru', ENT_QUOTES, 'UTF-8') ?> | Monitoring Santri</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/bootstrap.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar" aria-label="Menu guru">
            <div class="brand">
                <div><strong>Monitoring Santri</strong><small>Panel Guru</small></div>
            </div>
            <p class="nav-label">NAVIGASI</p>
            <nav aria-label="Navigasi utama">
                <a class="nav-item" href="<?= BASEURL ?>/Guru"<?= $currentPage === 'Dashboard Guru' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Dashboard
                </a>
            </nav>
            <p class="nav-label">KEGIATAN SANTRI</p>
            <nav aria-label="Kegiatan">
                <a class="nav-item" href="<?= BASEURL ?>/Guru/absensi"<?= $currentPage === 'Absensi Santri' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    Absensi
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Guru/hafalan"<?= $currentPage === 'Hafalan Santri' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    Hafalan
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Guru/pelanggaran"<?= $currentPage === 'Pelanggaran Santri' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Pelanggaran
                </a>
            </nav>
        </aside>
        <div class="workspace">
            <header class="topbar">
                <span class="topbar-label">Sistem Monitoring Santri</span>
                <div class="profile">
                    <span class="avatar" aria-hidden="true"><?= htmlspecialchars(strtoupper(substr($currentUser ?? 'U', 0, 1)), ENT_QUOTES, 'UTF-8') ?></span>
                    <?= $userName ?>
                    <a class="logout-link" href="<?= BASEURL ?>/Auth/logout">Keluar</a>
                </div>
            </header>
            <main>
