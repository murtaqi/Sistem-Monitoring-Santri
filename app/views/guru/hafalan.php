<?php
$records = $data['hafalan'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Kegiatan <span aria-hidden="true">/</span> Hafalan Santri</p>
<h1>Hafalan Santri</h1>
<p class="subtitle">Catatan setoran hafalan Al-Qur'an santri.</p>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
    </span>
    <div><span class="summary-label">Total Data Hafalan</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="hafalan-title">
    <div class="card-heading">
        <h2 id="hafalan-title">Daftar Hafalan</h2>
        <span class="badge"><?= count($records) ?> data</span>
        <button type="button" class="btn btn-success btn-sm" id="btnTambah" data-bs-toggle="modal" data-bs-target="#modalHafalan" style="margin-left:auto;">
            + Tambah Hafalan
        </button>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="tbl-search">Cari hafalan</label>
            <input type="search" id="tbl-search" placeholder="Cari nama santri, surah, atau juz..." autocomplete="off">
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
            <caption>Daftar data hafalan santri</caption>
            <thead>
                <tr>
                    <th scope="col" class="number">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Nama Santri</th>
                    <th scope="col">Surah</th>
                    <th scope="col">Juz</th>
                    <th scope="col">Predikat</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $index => $record): ?>
                    <tr data-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td><?= $escape($record['tanggal'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($record['nama_santri'] ?? '-') ?></td>
                        <td><?= $escape($record['surah'] ?? '-') ?></td>
                        <td><?= $escape($record['juz'] ?? '-') ?></td>
                        <td>
                            <?php
                            $predikat = strtolower($record['status_setoran'] ?? '');
                            $badgeColor = match($predikat) {
                                'lancar' => 'background:#d1fae5;color:#065f46;',
                                'kurang lancar' => 'background:#dbeafe;color:#1e40af;',
                                default => 'background:#f3f4f6;color:#374151;',
                            };
                            ?>
                            <span style="padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;<?= $badgeColor ?>"><?= $escape(ucfirst($record['predikat'] ?? '-')) ?></span>
                        </td>
                        <td class="actions">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHafalan"
                                    data-id="<?= $escape($record['id_hafalan'] ?? '') ?>"
                                    data-tanggal="<?= $escape($record['tanggal'] ?? '') ?>"
                                    data-santri="<?= $escape($record['nama_santri'] ?? '') ?>"
                                    data-surah="<?= $escape($record['surah'] ?? '') ?>"
                                    data-juz="<?= $escape($record['juz'] ?? '') ?>"
                                    data-ayat="<?= $escape($record['ayat'] ?? '') ?>"
                                    data-predikat="<?= $escape($record['predikat'] ?? '') ?>"
                                    data-catatan="<?= $escape($record['catatan'] ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Guru/deleteHafalan/<?= $escape($record['id_hafalan'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data hafalan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="row-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="7" class="empty"><strong id="empty-title">Belum ada data hafalan</strong><span id="empty-text">Data hafalan akan ditampilkan di sini setelah tersedia.</span></td>
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

<!-- Modal Tambah/Edit Hafalan -->
<div class="modal fade" id="modalHafalan" tabindex="-1" aria-labelledby="modalHafalanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--green); color:#fff;">
                <h5 class="modal-title" id="modalHafalanLabel">Tambah Hafalan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= BASEURL ?>/Guru/tambahHafalan" method="post" id="formHafalan">
                <div class="modal-body">
                    <input type="hidden" id="id_hafalan" name="id_hafalan">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nama_santri" class="form-label">Nama Santri</label>
                        <input type="text" class="form-control" id="nama_santri" name="nama_santri" required placeholder="Masukkan nama santri">
                    </div>
                    <div class="mb-3">
                        <label for="surah" class="form-label">Surah</label>
                        <input type="text" class="form-control" id="surah" name="surah" required placeholder="Contoh: Al-Baqarah">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="juz" class="form-label">Juz</label>
                            <input type="number" class="form-control" id="juz" name="juz" min="1" max="30" placeholder="1–30">
                        </div>
                        <div class="col-6">
                            <label for="ayat" class="form-label">Ayat</label>
                            <input type="text" class="form-control" id="ayat" name="ayat" placeholder="Contoh: 1-20">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="predikat" class="form-label">Predikat</label>
                        <select class="form-select" id="predikat" name="predikat" required>
                            <option value="">-- Pilih Predikat --</option>
                            <option value="Mumtaz">Mumtaz (Sempurna)</option>
                            <option value="Jayyid Jiddan">Jayyid Jiddan (Sangat Baik)</option>
                            <option value="Jayyid">Jayyid (Baik)</option>
                            <option value="Maqbul">Maqbul (Cukup)</option>
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
    const form = document.getElementById('formHafalan');
    const title = document.getElementById('modalHafalanLabel');
    const submitBtn = document.getElementById('btnSubmit');

    document.getElementById('btnTambah').addEventListener('click', () => {
        form.action = BASEURL + '/Guru/tambahHafalan';
        title.textContent = 'Tambah Hafalan';
        submitBtn.textContent = 'Tambah Data';
        form.reset();
        document.getElementById('id_hafalan').value = '';
        document.getElementById('tanggal').value = new Date().toISOString().slice(0,10);
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            form.action = BASEURL + '/Guru/updateHafalan';
            title.textContent = 'Edit Hafalan';
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('id_hafalan').value = btn.dataset.id;
            document.getElementById('tanggal').value = btn.dataset.tanggal;
            document.getElementById('nama_santri').value = btn.dataset.santri;
            document.getElementById('surah').value = btn.dataset.surah;
            document.getElementById('juz').value = btn.dataset.juz;
            document.getElementById('ayat').value = btn.dataset.ayat;
            document.getElementById('predikat').value = btn.dataset.predikat;
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

