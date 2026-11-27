<?php
$records = $data['records'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Administrasi <span aria-hidden="true">/</span> Data Guru</p>
<h1>Data Guru</h1>
<p class="subtitle">Daftar akun guru pada sistem monitoring santri.</p>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </span>
    <div><span class="summary-label">Total Guru</span><strong><?= count($records) ?></strong></div>
</div>
<section class="card" aria-labelledby="guru-title">
    <div class="card-heading">
        <h2 id="guru-title">Daftar Guru</h2>
        <span class="badge"><?= count($records) ?> akun</span>
        <button type="button" class="btn btn-success btn-sm" id="btnTambahGuru" data-bs-toggle="modal" data-bs-target="#modalGuru" style="margin-left:auto;">
            + Tambah Guru
        </button>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="guru-search">Cari guru</label>
            <input type="search" id="guru-search" placeholder="Cari nama atau username guru..." autocomplete="off">
        </div>
        <div class="field">
            <label for="guru-limit">Baris per halaman</label>
            <select id="guru-limit">
                <option value="10">10 baris</option>
                <option value="25">25 baris</option>
                <option value="50">50 baris</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table id="guru-table">
            <caption>Daftar akun guru sistem monitoring santri</caption>
            <thead>
                <tr><th scope="col" class="number">No</th><th scope="col">ID</th><th scope="col">Nama Lengkap</th><th scope="col">Username</th><th scope="col">Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($records as $index => $record): ?>
                    <tr data-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td class="admin-id"><?= $escape($record['id_user'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($record['nama_lengkap'] ?? '-') ?></td>
                        <td class="username"><?= $escape($record['username'] ?? '-') ?></td>
                        <td class="actions">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalGuru"
                                    data-id="<?= $escape($record['id_user'] ?? '') ?>"
                                    data-nama="<?= $escape($record['nama_lengkap'] ?? '') ?>"
                                    data-username="<?= $escape($record['username'] ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Admin/deleteUser/<?= $escape($record['id_user'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="row-empty" <?= count($records) ? 'hidden' : '' ?>>
                    <td colspan="5" class="empty"><strong id="empty-title">Belum ada data guru</strong><span id="empty-text">Data guru akan ditampilkan di sini setelah tersedia.</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-bottom">
        <p class="table-info" id="tbl-summary" role="status" aria-live="polite">Menampilkan <?= count($records) ? 1 : 0 ?>–<?= min(10, count($records)) ?> dari <?= count($records) ?> guru</p>
        <nav class="pagination" aria-label="Halaman tabel" id="tbl-pagination" hidden>
            <button type="button" id="tbl-prev" disabled>Sebelumnya</button>
            <span class="page-info" id="tbl-page"></span>
            <button type="button" id="tbl-next">Berikutnya</button>
        </nav>
    </div>
</section>

<!-- Modal Tambah/Edit Guru -->
<div class="modal fade" id="modalGuru" tabindex="-1" aria-labelledby="modalGuruLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--green); color:#fff;">
                <h5 class="modal-title" id="modalGuruLabel">Tambah Guru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= BASEURL ?>/Admin/tambahUser" method="post" id="formGuru">
                <div class="modal-body">
                    <input type="hidden" id="id_user" name="id_user">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required placeholder="Masukkan nama lengkap guru">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required placeholder="Masukkan username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm" name="confirm" required placeholder="Ulangi password">
                    </div>
                    <input type="hidden" id="role" name="role" value="guru">
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
    const form = document.getElementById('formGuru');
    const title = document.getElementById('modalGuruLabel');
    const submitBtn = document.getElementById('btnSubmit');

    document.getElementById('btnTambahGuru').addEventListener('click', () => {
        form.action = BASEURL + '/Admin/tambahUser';
        title.textContent = 'Tambah Guru';
        submitBtn.textContent = 'Tambah Data';
        form.reset();
        document.getElementById('id_user').value = '';
        document.getElementById('role').value = 'guru';
        document.getElementById('password').required = true;
        document.getElementById('confirm').required = true;
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            form.action = BASEURL + '/Admin/editUser';
            title.textContent = 'Edit Guru';
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('id_user').value = btn.dataset.id;
            document.getElementById('nama_lengkap').value = btn.dataset.nama;
            document.getElementById('username').value = btn.dataset.username;
            document.getElementById('password').value = '';
            document.getElementById('confirm').value = '';
            document.getElementById('password').required = false;
            document.getElementById('confirm').required = false;
            document.getElementById('role').value = 'guru';
        });
    });

    // --- Search & Pagination ---
    const search = document.getElementById('guru-search');
    if (!search) return;
    const limit = document.getElementById('guru-limit');
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
        document.getElementById('tbl-summary').textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} guru${kw ? ` (total ${rows.length} data)` : ''}`;
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
