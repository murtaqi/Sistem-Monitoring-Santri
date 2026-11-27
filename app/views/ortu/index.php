<?php
$records = $data['ortu'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Administrasi <span aria-hidden="true">/</span> Data Orang Tua</p>
<h1>Data Orang Tua</h1>
<p class="subtitle">Daftar data orang tua pada sistem monitoring santri.</p>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M21 21v-3a6 6 0 0 0-4-5.65"/></svg>
    </span>
    <div><span class="summary-label">Total Orang Tua</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="ortu-title">
    <div class="card-heading">
        <h2 id="ortu-title">Daftar Orang Tua</h2>
        <span class="badge"><?= count($records) ?> data</span>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="ortu-search">Cari orang tua</label>
            <input type="search" id="ortu-search" placeholder="Cari data orang tua..." autocomplete="off" aria-controls="ortu-table">
        </div>
        <div class="field">
            <label for="ortu-limit">Baris per halaman</label>
            <select id="ortu-limit" aria-controls="ortu-table">
                <option value="10">10 baris</option>
                <option value="25">25 baris</option>
                <option value="50">50 baris</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table id="ortu-table">
            <caption>Daftar data orang tua sistem monitoring santri</caption>
            <thead>
                <tr><th scope="col" class="number">No</th><th scope="col">ID Orang Tua</th><th scope="col">Nama Orang Tua</th><th scope="col">Username</th><th scope="col">Kontak</th><th scope="col">Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($records as $index => $record): ?>
                    <tr data-ortu-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td><?= $escape($record['id_ortu'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($record['nama_ortu'] ?? '-') ?></td>
                        <td class="username"><?= $escape($record['username'] ?? '-') ?></td>
                        <td><?= $escape($record['kontak'] ?? '-') ?></td>
                        <td class="actions">
                            <a href="/admin/santri/<?= $escape($record['id'] ?? '') ?>/edit" class="btn btn-sm btn-primary">Edit</a>
                            <a href="/admin/santri/<?= $escape($record['id'] ?? '') ?>/delete" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus santri ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="ortu-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="6" class="empty"><strong id="ortu-empty-title">Belum ada data orang tua</strong><span id="ortu-empty-text">Data orang tua akan ditampilkan di sini setelah tersedia.</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-bottom">
        <p class="table-info" id="ortu-summary" role="status" aria-live="polite">Menampilkan <?= count($records) ? 1 : 0 ?>–<?= min(10, count($records)) ?> dari <?= count($records) ?> orang tua</p>
        <nav class="pagination" aria-label="Halaman tabel orang tua" id="ortu-pagination" hidden>
            <button type="button" id="ortu-prev" aria-controls="ortu-table" disabled>Sebelumnya</button>
            <span class="page-info" id="ortu-page"></span>
            <button type="button" id="ortu-next" aria-controls="ortu-table">Berikutnya</button>
        </nav>
    </div>
</section>
<noscript><p>Pencarian dan perpindahan halaman membutuhkan JavaScript. Sepuluh data pertama ditampilkan.</p></noscript>

<div id="table-search-config"
     data-search-id="ortu-search"
     data-limit-id="ortu-limit"
     data-row-selector="[data-ortu-row]"
     data-prev-id="ortu-prev"
     data-next-id="ortu-next"
     data-empty-id="ortu-empty"
     data-empty-title-id="ortu-empty-title"
     data-empty-text-id="ortu-empty-text"
     data-summary-id="ortu-summary"
     data-page-id="ortu-page"
     data-pagination-id="ortu-pagination"
     data-entity-label="orang tua"></div>
<script src="<?= BASEURL ?>/js/table-search.js"></script>
