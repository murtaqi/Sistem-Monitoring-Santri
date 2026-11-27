<?php
$records = $data['santri'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Administrasi <span aria-hidden="true">/</span> Data Santri</p>
<h1>Data Santri</h1>
<p class="subtitle">Daftar data santri pada sistem monitoring santri.</p>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M21 21v-3a6 6 0 0 0-4-5.65"/></svg>
    </span>
    <div><span class="summary-label">Total Santri</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="santri-title">
    <div class="card-heading">
        <h2 id="santri-title">Daftar Santri</h2>
        <span class="badge"><?= count($records) ?> data</span>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="santri-search">Cari santri</label>
            <input type="search" id="santri-search" placeholder="Cari data santri..." autocomplete="off" aria-controls="santri-table">
        </div>
        <div class="field">
            <label for="santri-limit">Baris per halaman</label>
            <select id="santri-limit" aria-controls="santri-table">
                <option value="10">10 baris</option>
                <option value="25">25 baris</option>
                <option value="50">50 baris</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table id="santri-table">
            <caption>Daftar data santri sistem monitoring santri</caption>
            <thead>
                <tr><th scope="col" class="number">No</th><th scope="col">NIS</th><th scope="col">Nama Santri</th><th scope="col">Kelas</th><th scope="col">Target Juz</th><th scope="col">Nomor Telepon</th><th scope="col">Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($records as $index => $record): ?>
                    <tr data-santri-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td><?= $escape($record['nis'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($record['nama_santri'] ?? '-') ?></td>
                        <td><?= $escape($record['kelas'] ?? '-') ?></td>
                        <td><?= $escape($record['target_juz'] ?? '-') ?></td>
                        <td><?= $escape($record['kontak'] ?? '-') ?></td>
                        <td class="actions">
                            <a href="/admin/santri/<?= $escape($record['id'] ?? '') ?>/edit" class="btn btn-sm btn-primary">Edit</a>
                            <a href="/admin/santri/<?= $escape($record['id'] ?? '') ?>/delete" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus santri ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="santri-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="9" class="empty"><strong id="santri-empty-title">Belum ada data santri</strong><span id="santri-empty-text">Data santri akan ditampilkan di sini setelah tersedia.</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-bottom">
        <p class="table-info" id="santri-summary" role="status" aria-live="polite">Menampilkan <?= count($records) ? 1 : 0 ?>–<?= min(10, count($records)) ?> dari <?= count($records) ?> santri</p>
        <nav class="pagination" aria-label="Halaman tabel santri" id="santri-pagination" hidden>
            <button type="button" id="santri-prev" aria-controls="santri-table" disabled>Sebelumnya</button>
            <span class="page-info" id="santri-page"></span>
            <button type="button" id="santri-next" aria-controls="santri-table">Berikutnya</button>
        </nav>
    </div>
</section>
<noscript><p>Pencarian dan perpindahan halaman membutuhkan JavaScript. Sepuluh data pertama ditampilkan.</p></noscript>

<div id="table-search-config"
     data-search-id="santri-search"
     data-limit-id="santri-limit"
     data-row-selector="[data-santri-row]"
     data-prev-id="santri-prev"
     data-next-id="santri-next"
     data-empty-id="santri-empty"
     data-empty-title-id="santri-empty-title"
     data-empty-text-id="santri-empty-text"
     data-summary-id="santri-summary"
     data-page-id="santri-page"
     data-pagination-id="santri-pagination"
     data-entity-label="santri"></div>
<script src="<?= BASEURL ?>/js/table-search.js"></script>
