<?php
$admins = $data['admin'] ?? [];
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>
<p class="breadcrumb">Administrasi <span aria-hidden="true">/</span> User Admin</p>
<h1>User Admin</h1>
<p class="subtitle">Daftar akun administrator pada sistem monitoring santri.</p>
<div class="summary">
    <span class="summary-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </span>
    <div><span class="summary-label">Total User Admin</span><strong><?= count($admins) ?></strong></div>
</div>
<section class="card" aria-labelledby="admin-title">
    <div class="card-heading">
        <h2 id="admin-title">Daftar Administrator</h2>
        <span class="badge"><?= count($admins) ?> akun</span>
        <button type="button" class="btn btn-success btn-sm" id="btnTambahAdmin" data-bs-toggle="modal" data-bs-target="#modalAdmin" style="margin-left:auto;">
            + Tambah Admin
        </button>
    </div>
    <div class="toolbar">
        <div class="field search-field">
            <label for="admin-search">Cari administrator</label>
            <input type="search" id="admin-search" placeholder="Cari ID, nama, atau username..." autocomplete="off">
        </div>
        <div class="field">
            <label for="admin-limit">Baris per halaman</label>
            <select id="admin-limit">
                <option value="10">10 baris</option>
                <option value="25">25 baris</option>
                <option value="50">50 baris</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table id="admin-table">
            <caption>Daftar akun administrator sistem monitoring santri</caption>
            <thead>
                <tr><th scope="col" class="number">No</th><th scope="col">ID</th><th scope="col">Nama Lengkap</th><th scope="col">Username</th><th scope="col">Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $index => $admin): ?>
                    <tr data-row <?= $index >= 10 ? 'hidden' : '' ?>>
                        <td class="number"><?= $index + 1 ?></td>
                        <td class="admin-id"><?= $escape($admin['id_user'] ?? '-') ?></td>
                        <td class="admin-name"><?= $escape($admin['nama_lengkap'] ?? '-') ?></td>
                        <td class="username"><?= $escape($admin['username'] ?? '-') ?></td>
                        <td class="actions">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAdmin"
                                    data-id="<?= $escape($admin['id_user'] ?? '') ?>"
                                    data-nama="<?= $escape($admin['nama_lengkap'] ?? '') ?>"
                                    data-username="<?= $escape($admin['username'] ?? '') ?>">
                                Edit
                            </button>
                            <a href="<?= BASEURL ?>/Admin/deleteUser/<?= $escape($admin['id_user'] ?? '') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr id="row-empty" <?= count($admins) ? 'hidden' : '' ?>>
                    <td colspan="5" class="empty"><strong id="empty-title">Belum ada user admin</strong><span id="empty-text">Data administrator akan ditampilkan di sini setelah tersedia.</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-bottom">
        <p class="table-info" id="tbl-summary" role="status" aria-live="polite">Menampilkan <?= count($admins) ? 1 : 0 ?>–<?= min(10, count($admins)) ?> dari <?= count($admins) ?> admin</p>
        <nav class="pagination" aria-label="Halaman tabel" id="tbl-pagination" hidden>
            <button type="button" id="tbl-prev" disabled>Sebelumnya</button>
            <span class="page-info" id="tbl-page"></span>
            <button type="button" id="tbl-next">Berikutnya</button>
        </nav>
    </div>
</section>

<!-- Modal Tambah/Edit Admin -->
<div class="modal fade" id="modalAdmin" tabindex="-1" aria-labelledby="modalAdminLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--green); color:#fff;">
                <h5 class="modal-title" id="modalAdminLabel">Tambah Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="<?= BASEURL ?>/Admin/tambahUser" method="post" id="formAdmin">
                <div class="modal-body">
                    <input type="hidden" id="id_user" name="id_user">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required placeholder="Masukkan nama lengkap">
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
                    <input type="hidden" id="role" name="role" value="admin">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" id="btnSubmitAdmin">Tambah Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(() => {
    // --- Modal Tambah / Edit ---
    const modalEl = document.getElementById('modalAdmin');
    const form = document.getElementById('formAdmin');
    const title = document.getElementById('modalAdminLabel');
    const submitBtn = document.getElementById('btnSubmitAdmin');

    document.getElementById('btnTambahAdmin').addEventListener('click', () => {
        form.action = BASEURL + '/Admin/tambahUser';
        title.textContent = 'Tambah Admin';
        submitBtn.textContent = 'Tambah Data';
        form.reset();
        document.getElementById('id_user').value = '';
        document.getElementById('role').value = 'admin';
        document.getElementById('password').required = true;
        document.getElementById('confirm').required = true;
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            form.action = BASEURL + '/Admin/updateUser';
            title.textContent = 'Edit Admin';
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('id_user').value = btn.dataset.id;
            document.getElementById('nama_lengkap').value = btn.dataset.nama;
            document.getElementById('username').value = btn.dataset.username;
            document.getElementById('password').value = '';
            document.getElementById('confirm').value = '';
            document.getElementById('password').required = false;
            document.getElementById('confirm').required = false;
            document.getElementById('role').value = 'admin';
        });
    });

    // --- Search & Pagination ---
    const search = document.getElementById('admin-search');
    if (!search) return;
    const limit = document.getElementById('admin-limit');
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
        document.getElementById('empty-title').textContent = rows.length ? 'Admin tidak ditemukan' : 'Belum ada user admin';
        document.getElementById('empty-text').textContent = rows.length ? 'Coba gunakan kata kunci lain.' : 'Data administrator akan ditampilkan di sini setelah tersedia.';
        document.getElementById('tbl-summary').textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} admin${kw ? ` (total ${rows.length} akun)` : ''}`;
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
