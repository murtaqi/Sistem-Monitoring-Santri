<?php
$records = $data['absensi'] ?? [];
$santriList = $data['santri'] ?? [];
$kelasList = array_values(array_unique(array_filter(array_map(static fn($s) => trim((string)($s['kelas'] ?? '')), $santriList))));
sort($kelasList);
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Kegiatan <span aria-hidden="true">/</span> Absensi Santri</p>
<h1>Absensi Santri</h1>
<p class="subtitle">Catatan kehadiran santri harian.</p>
<?php Flasher::flash(); ?>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </span>
    <div><span class="summary-label">Total Data Absensi</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="absensi-title">
    <div class="card-heading">
        <h2 id="absensi-title">Daftar Absensi</h2>
        <span class="badge"><?= count($records) ?> data</span>
        <button type="button" class="btn btn-success btn-sm" id="btnTambah" data-bs-toggle="modal" data-bs-target="#modalAbsensi" style="margin-left:auto;">
            + Tambah Absensi
        </button>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="tbl-search">Cari absensi</label>
            <input type="search" id="tbl-search" placeholder="Cari nama santri, NIS, tanggal, atau status..." autocomplete="off">
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
            <caption>Daftar data absensi santri</caption>
            <thead>
                <tr>
                    <th scope="col" class="number">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nama Santri</th>
                    <th scope="col">Status</th>
                    <th scope="col">Keterangan</th>
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
                        <td>
                            <?php
                            $statusLabel = $record['status_kehadiran'] ?? '-';
                            $status = strtolower($statusLabel);
                            $badgeColor = match($status) {
                                'hadir' => 'background:#d1fae5;color:#065f46;',
                                'izin' => 'background:#dbeafe;color:#1e40af;',
                                'sakit' => 'background:#fef3c7;color:#92400e;',
                                'alpha' => 'background:#fee2e2;color:#991b1b;',
                                default => 'background:#f3f4f6;color:#374151;',
                            };
                            ?>
                            <span style="padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;<?= $badgeColor ?>"><?= $escape($statusLabel) ?></span>
                        </td>
                        <td><?= $escape($record['catatan'] ?? '-') ?></td>
                        <td class="actions">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAbsensi"
                                    data-id="<?= $escape($record['id_absensi'] ?? '') ?>"
                                    data-tanggal="<?= $escape($record['tanggal'] ?? '') ?>"
                                    data-id-santri="<?= $escape($record['id_santri'] ?? '') ?>"
                                    data-nis="<?= $escape($record['nis'] ?? '') ?>"
                                    data-status="<?= $escape($record['status_kehadiran'] ?? '') ?>"
                                    data-catatan="<?= $escape($record['catatan'] ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Guru/deleteAbsensi/<?= $escape($record['id_absensi'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data absensi ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="row-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="6" class="empty"><strong id="empty-title">Belum ada data absensi</strong><span id="empty-text">Data absensi akan ditampilkan di sini setelah tersedia.</span></td>
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

<!-- Modal Tambah/Edit Absensi -->
<div class="modal fade" id="modalAbsensi" tabindex="-1" aria-labelledby="modalAbsensiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--green); color:#fff;">
                <h5 class="modal-title" id="modalAbsensiLabel">Tambah Absensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= BASEURL ?>/Guru/tambahAbsensi" method="post" id="formAbsensi">
                <div class="modal-body">
                    <input type="hidden" id="id_absensi" name="id_absensi">
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
                        <label for="status_kehadiran" class="form-label">Status Kehadiran</label>
                        <select class="form-select" id="status_kehadiran" name="status_kehadiran" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
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
     data-form-id="formAbsensi"
     data-title-id="modalAbsensiLabel"
     data-id-field="id_absensi"
     data-tambah-url="/Guru/tambahAbsensi"
     data-update-url="/Guru/updateAbsensi"
     data-tambah-title="Tambah Absensi"
     data-edit-title="Edit Absensi"
     data-field-map='{"status_kehadiran":"status","catatan":"catatan"}'></div>
<script src="<?= BASEURL ?>/js/guru-kegiatan.js"></script>
