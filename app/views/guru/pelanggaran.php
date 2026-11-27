<?php
$records = $data['pelanggaran'] ?? [];
$santriList = $data['santri'] ?? [];
$kelasList = array_values(array_unique(array_filter(array_map(static fn($s) => trim((string)($s['kelas'] ?? '')), $santriList))));
sort($kelasList);
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Kegiatan <span aria-hidden="true">/</span> Pelanggaran Santri</p>
<h1>Pelanggaran Santri</h1>
<p class="subtitle">Catatan pelanggaran dan kedisiplinan santri.</p>
<?php Flasher::flash(); ?>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </span>
    <div><span class="summary-label">Total Data Pelanggaran</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="pelanggaran-title">
    <div class="card-heading">
        <h2 id="pelanggaran-title">Daftar Pelanggaran</h2>
        <span class="badge"><?= count($records) ?> data</span>
        <button type="button" class="btn btn-success btn-sm" id="btnTambah" data-bs-toggle="modal" data-bs-target="#modalPelanggaran" style="margin-left:auto;">
            + Catat Pelanggaran
        </button>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="tbl-search">Cari pelanggaran</label>
            <input type="search" id="tbl-search" placeholder="Cari nama santri, NIS, jenis, atau tanggal..." autocomplete="off">
        </div>
        <div class="field">
            <label for="tbl-kelas">Filter Kelas</label>
            <select id="tbl-kelas">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelasList as $kelas): ?>
                    <option value="<?= $escape($kelas) ?>"><?= $escape($kelas) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="tbl-limit">Baris per halaman</label>
            <select id="tbl-limit">
                <option value="10">10 baris</option>
                <option value="25">25 baris</option>
                <option value="50">50 baris</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table id="tbl-data">
            <caption>Daftar data pelanggaran santri</caption>
            <thead>
                <tr>
                    <th scope="col" class="number">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nama Santri</th>
                    <th scope="col">Jenis Pelanggaran</th>
                    <th scope="col">Sanksi</th>
                    <th scope="col">Status Sanksi</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $index => $record): ?>
                    <tr data-row data-kelas="<?= $escape($record['kelas'] ?? '') ?>" <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td><?= $escape($record['tanggal'] ?? '-') ?></td>
                        <td class="admin-name">
                            <?= $escape($record['nama_santri'] ?? '-') ?>
                            <small style="display:block;color:#6c757d;font-weight:400;">NIS: <?= $escape($record['nis'] ?? '-') ?> &middot; Kelas <?= $escape($record['kelas'] ?? '-') ?></small>
                        </td>
                        <td><?= $escape($record['jenis_pelanggaran'] ?? '-') ?></td>
                        <td><?= $escape($record['sanksi'] ?? '-') ?></td>
                        <td>
                            <?php
                            $statusSanksi = $record['status_sanksi'] ?? 'Belum Selesai';
                            $statusColor = strtolower($statusSanksi) === 'selesai'
                                ? 'background:#d1fae5;color:#065f46;'
                                : 'background:#fee2e2;color:#991b1b;';
                            ?>
                            <span style="padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;<?= $statusColor ?>"><?= $escape($statusSanksi) ?></span>
                        </td>
                        <td class="actions">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPelanggaran"
                                    data-id="<?= $escape($record['id_pelanggaran'] ?? '') ?>"
                                    data-tanggal="<?= $escape($record['tanggal'] ?? '') ?>"
                                    data-id-santri="<?= $escape($record['id_santri'] ?? '') ?>"
                                    data-nis="<?= $escape($record['nis'] ?? '') ?>"
                                    data-jenis="<?= $escape($record['jenis_pelanggaran'] ?? '') ?>"
                                    data-sanksi="<?= $escape($record['sanksi'] ?? '') ?>"
                                    data-status="<?= $escape($record['status_sanksi'] ?? 'Belum Selesai') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Guru/deletePelanggaran/<?= $escape($record['id_pelanggaran'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data pelanggaran ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="row-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="7" class="empty"><strong id="empty-title">Belum ada data pelanggaran</strong><span id="empty-text">Data pelanggaran akan ditampilkan di sini setelah tersedia.</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-bottom">
        <p class="table-info" id="tbl-summary" role="status" aria-live="polite">Menampilkan <?= count($records) ? 1 : 0 ?>–<?= min(10, count($records)) ?> dari <?= count($records) ?> data</p>
        <nav class="pagination" aria-label="Halaman tabel" id="tbl-pagination" hidden>
            <button type="button" id="tbl-prev" disabled>Sebelumnya</button>
            <span class="page-info" id="tbl-page"></span>
            <button type="button" id="tbl-next">Berikutnya</button>
        </nav>
    </div>
</section>

<!-- Modal Tambah/Edit Pelanggaran -->
<div class="modal fade" id="modalPelanggaran" tabindex="-1" aria-labelledby="modalPelanggaranLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--green); color:#fff;">
                <h5 class="modal-title" id="modalPelanggaranLabel">Catat Pelanggaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= BASEURL ?>/Guru/tambahPelanggaran" method="post" id="formPelanggaran">
                <div class="modal-body">
                    <input type="hidden" id="id_pelanggaran" name="id_pelanggaran">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nis_santri" class="form-label">NIS Santri</label>
                        <input type="hidden" id="id_santri" name="id_santri">
                        <input type="text" class="form-control" id="nis_santri" name="nis" list="list-nis-santri" autocomplete="off" required placeholder="Ketik angka NIS santri...">
                        <datalist id="list-nis-santri">
                            <?php foreach ($santriList as $santri): ?>
                                <option value="<?= $escape($santri['nis'] ?? '') ?>"
                                        data-id="<?= $escape($santri['id_santri'] ?? '') ?>"
                                        data-nama="<?= $escape($santri['nama_santri'] ?? '') ?>"
                                        data-kelas="<?= $escape($santri['kelas'] ?? '-') ?>">
                                    <?= $escape($santri['nama_santri'] ?? '') ?> (Kelas <?= $escape($santri['kelas'] ?? '-') ?>)
                                </option>
                            <?php endforeach; ?>
                        </datalist>
                        <small id="santri-info" class="form-text text-muted">Ketik angka NIS untuk melihat daftar santri.</small>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_pelanggaran" class="form-label">Jenis Pelanggaran</label>
                        <input type="text" class="form-control" id="jenis_pelanggaran" name="jenis_pelanggaran" required placeholder="Contoh: Terlambat sholat berjamaah">
                    </div>
                    <div class="mb-3">
                        <label for="sanksi" class="form-label">Sanksi / Tindakan</label>
                        <textarea class="form-control" id="sanksi" name="sanksi" rows="2" required placeholder="Contoh: Baca 1/2 juz"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status_sanksi" class="form-label">Status Sanksi</label>
                        <select class="form-select" id="status_sanksi" name="status_sanksi" required>
                            <option value="Belum Selesai">Belum Selesai</option>
                            <option value="Selesai">Selesai</option>
                        </select>
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

<div id="guru-kegiatan-config"
     data-form-id="formPelanggaran"
     data-title-id="modalPelanggaranLabel"
     data-id-field="id_pelanggaran"
     data-tambah-url="/Guru/tambahPelanggaran"
     data-update-url="/Guru/updatePelanggaran"
     data-tambah-title="Catat Pelanggaran"
     data-edit-title="Edit Pelanggaran"
     data-field-map='{"jenis_pelanggaran":"jenis","sanksi":"sanksi","status_sanksi":"status"}'></div>
<script src="<?= BASEURL ?>/js/guru-kegiatan.js"></script>

