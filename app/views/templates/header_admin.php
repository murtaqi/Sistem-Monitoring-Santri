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
    <title><?= htmlspecialchars($data['judul'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?> | Monitoring Santri</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/bootstrap.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css?v=<?= @filemtime(__DIR__ . '/../../../public/css/style.css') ?: '2' ?>">
</head>
<body>
    <div class="layout">
        <aside class="sidebar" aria-label="Menu admin">
            <div class="brand">
                <div><strong>Monitoring Santri</strong><small>Panel Administrasi</small></div>
            </div>
            <p class="nav-label">NAVIGASI</p>
            <nav aria-label="Navigasi utama">
                <a class="nav-item" href="<?= BASEURL ?>/Admin"<?= $currentPage === 'Dashboard Admin' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Dashboard
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Admin/laporan"<?= $currentPage === 'Dashboard Sistem Informasi' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 3v18h18"/><path d="M7 15l3-4 3 3 5-7"/></svg>
                    Dashboard Sistem Informasi
                </a>
            </nav>
            <p class="nav-label">MASTER DATA</p>
            <nav aria-label="Master data">
                <a class="nav-item" href="<?= BASEURL ?>/Admin/admin"<?= $currentPage === 'Data Admin' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Data Admin
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Admin/guru"<?= $currentPage === 'Data Guru' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Data Guru
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Admin/ortu"<?= $currentPage === 'Data Orang Tua' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M21 21v-3a6 6 0 0 0-4-5.65"/></svg>
                    Data Orang Tua
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Admin/santri"<?= $currentPage === 'Data Santri' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    Data Santri
                </a>
            </nav>
            <p class="nav-label">LAPORAN</p>
            <nav aria-label="Laporan">
                <a class="nav-item" href="<?= BASEURL ?>/Admin/laporanAbsensi"<?= $currentPage === 'Laporan Absensi Santri' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Laporan Absensi
                </a>
                <a class="nav-item" href="<?= BASEURL ?>/Admin/laporanSantri"<?= $currentPage === 'Laporan Kondisi Santri' ? ' aria-current="page"' : '' ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                    Laporan Kondisi Santri
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
