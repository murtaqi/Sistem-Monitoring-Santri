<?php
$records = $data['records'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Administrasi <span aria-hidden="true">/</span> Data Santri</p>
<h1>Data Santri</h1>
<p class="subtitle">Daftar data santri pada sistem monitoring santri.</p>
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
                                    data-kontak="<?= $escape($record['kontak'] ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Admin/deleteSantri/<?= $escape($record['id_santri'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus santri ini?')">Hapus</a>
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
            <form action="<?= BASEURL ?>/Admin/tambahSantri" method="post" id="formSantri">
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

<script>
(() => {
    const form = document.getElementById('formSantri');
    const title = document.getElementById('modalSantriLabel');
    const submitBtn = document.getElementById('btnSubmit');

    document.getElementById('btnTambahSantri').addEventListener('click', () => {
        form.action = BASEURL + '/Admin/tambahSantri';
        title.textContent = 'Tambah Santri';
        submitBtn.textContent = 'Tambah Data';
        form.reset();
        document.getElementById('id_santri').value = '';
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            form.action = BASEURL + '/Admin/updateSantri';
            title.textContent = 'Edit Santri';
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('id_santri').value = btn.dataset.id;
            document.getElementById('nis').value = btn.dataset.nis;
            document.getElementById('nama_santri').value = btn.dataset.nama;
            document.getElementById('kelas').value = btn.dataset.kelas;
            document.getElementById('target_juz').value = btn.dataset.juz;
            document.getElementById('kontak').value = btn.dataset.kontak;
        });
    });

    // --- Search & Pagination ---
    const search = document.getElementById('santri-search');
    if (!search) return;
    const limit = document.getElementById('santri-limit');
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
        document.getElementById('tbl-summary').textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} santri${kw ? ` (total ${rows.length} data)` : ''}`;
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
