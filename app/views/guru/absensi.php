<?php
$records = $data['absensi'] ?? [];
$santriList = $data['santri'] ?? [];
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
            <input type="search" id="tbl-search" placeholder="Cari nama santri, tanggal, atau status..." autocomplete="off">
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
                    <tr data-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td><?= $escape($record['tanggal'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($record['nama_santri'] ?? '-') ?></td>
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
                        <label for="id_santri" class="form-label">Santri</label>
                        <select class="form-select" id="id_santri" name="id_santri" required>
                            <option value="">-- Pilih Santri --</option>
                            <?php foreach ($santriList as $santri): ?>
                                <option value="<?= $escape($santri['id_santri'] ?? '') ?>">
                                    <?= $escape($santri['nama_santri'] ?? '') ?> (<?= $escape($santri['nis'] ?? '-') ?> - <?= $escape($santri['kelas'] ?? '-') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
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

<script>
(() => {
    const form = document.getElementById('formAbsensi');
    const title = document.getElementById('modalAbsensiLabel');
    const submitBtn = document.getElementById('btnSubmit');

    document.getElementById('btnTambah').addEventListener('click', () => {
        form.action = BASEURL + '/Guru/tambahAbsensi';
        title.textContent = 'Tambah Absensi';
        submitBtn.textContent = 'Tambah Data';
        form.reset();
        document.getElementById('id_absensi').value = '';
        document.getElementById('tanggal').value = new Date().toISOString().slice(0,10);
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            form.action = BASEURL + '/Guru/updateAbsensi';
            title.textContent = 'Edit Absensi';
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('id_absensi').value = btn.dataset.id;
            document.getElementById('tanggal').value = btn.dataset.tanggal;
            document.getElementById('id_santri').value = btn.dataset.idSantri;
            document.getElementById('status_kehadiran').value = btn.dataset.status;
            document.getElementById('catatan').value = btn.dataset.catatan;
        });
    });

    // --- Search & Pagination ---
    const search = document.getElementById('tbl-search');
    if (!search) return;
    const limit = document.getElementById('tbl-limit');
    const rows = Array.from(document.querySelectorAll('[data-row]'));
    const records = rows.map(row => ({ row, text: Array.from(row.cells).slice(1).map(c => c.textContent).join(' ').toLocaleLowerCase('id') }));
    const prev = document.getElementById('tbl-prev');
    const next = document.getElementById('tbl-next');
    let page = 1;
    const render = () => {
        const kw = search.value.trim().toLocaleLowerCase('id');
        const matches = records.filter(r => r.text.includes(kw));
        const size = Number(limit.value);
        const pages = Math.max(1, Math.ceil(matches.length / size));
        page = Math.min(page, pages);
        const start = (page - 1) * size;
        rows.forEach(r => { r.row.hidden = true; });
        matches.slice(start, start + size).forEach((r, i) => { r.row.hidden = false; r.row.cells[0].textContent = start + i + 1; });
        document.getElementById('row-empty').hidden = matches.length > 0;
        document.getElementById('tbl-summary').textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} data${kw ? ` (total ${rows.length})` : ''}`;
        document.getElementById('tbl-page').textContent = `${page} / ${pages}`;
        document.getElementById('tbl-pagination').hidden = false;
        prev.disabled = page <= 1;
        next.disabled = page >= pages;
    };
    search.addEventListener('input', () => { page = 1; render(); });
    limit.addEventListener('change', () => { page = 1; render(); });
    prev.addEventListener('click', () => { if (page > 1) { page--; render(); } });
    next.addEventListener('click', () => { page++; render(); });
    render();
})();
</script>
