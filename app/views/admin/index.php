<?php
$totalAdmin = count($data['admin'] ?? []);
$totalGuru  = count($data['guru'] ?? []);
$totalOrtu  = count($data['ortu'] ?? []);
$totalSantri = count($data['santri'] ?? []);
$currentUser = $_SESSION['user'] ?? [];
$userName = htmlspecialchars($currentUser['nama_lengkap'] ?? 'Pengguna');
?>
<p class="breadcrumb">Dashboard</p>
<h1>Selamat Datang, <?= $userName ?> 👋</h1>
<p class="subtitle">Ringkasan data sistem monitoring santri.</p>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:20px; margin-bottom:32px;">
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </span>
        <div><span class="summary-label">Total Admin</span><strong><?= $totalAdmin ?></strong></div>
    </div>
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </span>
        <div><span class="summary-label">Total Guru</span><strong><?= $totalGuru ?></strong></div>
    </div>
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M21 21v-3a6 6 0 0 0-4-5.65"/></svg>
        </span>
        <div><span class="summary-label">Total Orang Tua</span><strong><?= $totalOrtu ?></strong></div>
    </div>
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
        </span>
        <div><span class="summary-label">Total Santri</span><strong><?= $totalSantri ?></strong></div>
    </div>
</div>

<section class="card" aria-labelledby="quickaction-title">
    <div class="card-heading">
        <h2 id="quickaction-title">Pintasan Cepat</h2>
    </div>
    <div style="padding:24px; display:flex; flex-wrap:wrap; gap:12px;">
        <a href="<?= BASEURL ?>/Admin/admin" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">📋 Kelola Admin</a>
        <a href="<?= BASEURL ?>/Admin/guru" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">👨‍🏫 Kelola Guru</a>
        <a href="<?= BASEURL ?>/Admin/ortu" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">👪 Kelola Orang Tua</a>
        <a href="<?= BASEURL ?>/Admin/santri" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">🎓 Kelola Santri</a>
    </div>
</section>

