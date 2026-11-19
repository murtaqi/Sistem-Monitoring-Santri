<?php
$records = $data['guru'] ?? [];
$currentUser = $_SESSION['user'] ?? [];
$userName = htmlspecialchars($currentUser['nama_lengkap'] ?? 'Pengguna');
?>
<p class="breadcrumb">Dashboard</p>
<h1>Selamat Datang, <?= $userName ?> 👋</h1>
<p class="subtitle">Panel guru — kelola absensi, hafalan, dan pelanggaran santri.</p>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:20px; margin-bottom:32px;">
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </span>
        <div><span class="summary-label">Menu</span><strong>Absensi</strong></div>
    </div>
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </span>
        <div><span class="summary-label">Menu</span><strong>Hafalan</strong></div>
    </div>
    <div class="summary" style="max-width:none;">
        <span class="summary-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </span>
        <div><span class="summary-label">Menu</span><strong>Pelanggaran</strong></div>
    </div>
</div>

<section class="card" aria-labelledby="quickaction-title">
    <div class="card-heading">
        <h2 id="quickaction-title">Pintasan Cepat</h2>
    </div>
    <div style="padding:24px; display:flex; flex-wrap:wrap; gap:12px;">
        <a href="<?= BASEURL ?>/Guru/absensi" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">✅ Catat Absensi</a>
        <a href="<?= BASEURL ?>/Guru/hafalan" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">📖 Input Hafalan</a>
        <a href="<?= BASEURL ?>/Guru/pelanggaran" class="btn btn-outline-success btn-sm" style="padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">⚠️ Catat Pelanggaran</a>
    </div>
</section>
