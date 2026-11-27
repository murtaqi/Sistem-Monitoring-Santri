<?php
$records = $data['records'] ?? [];
$ortuList = $data['ortu'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Administrasi <span aria-hidden="true">/</span> Data Santri</p>
<h1>Data Santri</h1>
<p class="subtitle">Daftar data santri pada sistem monitoring santri.</p>
<?php Flasher::flash(); ?>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
    </span>
    <div><span class="summary-label">Total Santri</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="santri-title">
    <div class="card-heading">
        <h2 id="santri-title">Daftar Santri</h2>
        <span class="badge"><?= count($records) ?> data</span>
        <button type="button" class="btn btn-success btn-sm" id="btnTambahSantri" data-bs-toggle="modal" data-bs-target="#modalSantri" style="margin-left:auto;">
            + Tambah Santri
        </button>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="santri-search">Cari santri</label>
            <input type="search" id="santri-search" placeholder="Cari NIS, nama, atau kelas santri..." autocomplete="off">
        </div>
        <div class="field">
            <label for="santri-limit">Baris per halaman</label>
            <select id="santri-limit">
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
                <tr>
                    <th scope="col" class="number">No</th>
                    <th scope="col">NIS</th>
                    <th scope="col">Nama Santri</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Target Juz</th>
                    <th scope="col">Kontak</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $index => $record): ?>
                    <tr data-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td><?= $escape($record['nis'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($record['nama_santri'] ?? '-') ?></td>
                        <td><?= $escape($record['kelas'] ?? '-') ?></td>
                        <td><?= $escape($record['target_juz'] ?? '-') ?></td>
                        <td><?= $escape($record['kontak'] ?? '-') ?></td>
                        <td class="actions">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalSantri"
                                    data-id="<?= $escape($record['id_santri'] ?? '') ?>"
                                    data-nis="<?= $escape($record['nis'] ?? '') ?>"
                                    data-nama="<?= $escape($record['nama_santri'] ?? '') ?>"
                                    data-kelas="<?= $escape($record['kelas'] ?? '') ?>"
                                    data-juz="<?= $escape($record['target_juz'] ?? '') ?>"
                                    data-ortu="<?= $escape($record['id_ortu'] ?? '') ?>"
                                    data-kontak="<?= $escape($record['kontak'] ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Santri/hapusSantri/<?= $escape($record['id_santri'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus santri ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="row-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="7" class="empty"><strong id="empty-title">Belum ada data santri</strong><span id="empty-text">Data santri akan ditampilkan di sini setelah tersedia.</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-bottom">
        <p class="table-info" id="tbl-summary" role="status" aria-live="polite">Menampilkan <?= count($records) ? 1 : 0 ?>–<?= min(10, count($records)) ?> dari <?= count($records) ?> santri</p>
        <nav class="pagination" aria-label="Halaman tabel" id="tbl-pagination" hidden>
            <button type="button" id="tbl-prev" disabled>Sebelumnya</button>
            <span class="page-info" id="tbl-page"></span>
            <button type="button" id="tbl-next">Berikutnya</button>
        </nav>
    </div>
</section>

<!-- Modal Tambah/Edit Santri -->
<div class="modal fade" id="modalSantri" tabindex="-1" aria-labelledby="modalSantriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--green); color:#fff;">
                <h5 class="modal-title" id="modalSantriLabel">Tambah Santri</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= BASEURL ?>/Santri/tambahSantri" method="post" id="formSantri">
                <div class="modal-body">
                    <input type="hidden" id="id_santri" name="id_santri">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required placeholder="Masukkan NIS santri">
                    </div>
                    <div class="mb-3">
                        <label for="nama_santri" class="form-label">Nama Santri</label>
                        <input type="text" class="form-control" id="nama_santri" name="nama_santri" required placeholder="Masukkan nama lengkap santri">
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" required placeholder="Contoh: 7A, 8B, 9C">
                    </div>
                    <div class="mb-3">
                        <label for="target_juz" class="form-label">Target Juz</label>
                        <input type="number" class="form-control" id="target_juz" name="target_juz" min="1" max="30" placeholder="1 - 30">
                    </div>
                    <div class="mb-3">
                        <label for="id_ortu" class="form-label">Orang Tua / Wali</label>
                        <select class="form-select" id="id_ortu" name="id_ortu" required>
                            <option value="">-- Pilih Orang Tua --</option>
                            <?php foreach ($ortuList as $ortu): ?>
                                <option value="<?= $escape($ortu['id_user'] ?? '') ?>">
                                    <?= $escape($ortu['nama_lengkap'] ?? '') ?> (<?= $escape($ortu['username'] ?? '') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kontak" class="form-label">Kontak Orang Tua</label>
                        <input type="text" class="form-control" id="kontak" name="kontak" placeholder="Nomor telepon orang tua">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" id="btnSubmit">Tambah Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="admin-data-config"
     data-mode="santri"
     data-form-id="formSantri"
     data-title-id="modalSantriLabel"
     data-submit-id="btnSubmit"
     data-tambah-btn-id="btnTambahSantri"
     data-id-field="id_santri"
     data-tambah-url="/Santri/tambahSantri"
     data-update-url="/Santri/editSantri"
     data-tambah-title="Tambah Santri"
     data-edit-title="Edit Santri"
     data-field-map='{"nis":"nis","nama_santri":"nama","kelas":"kelas","target_juz":"juz","id_ortu":"ortu","kontak":"kontak"}'
     data-search-id="santri-search"
     data-limit-id="santri-limit"
     data-entity-label="santri"></div>
<script src="<?= BASEURL ?>/js/admin-data.js"></script>
