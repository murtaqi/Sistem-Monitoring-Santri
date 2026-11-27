document.addEventListener('DOMContentLoaded', () => {
    const configEl = document.getElementById('guru-kegiatan-config');
    if (!configEl) return;

    const form = document.getElementById(configEl.dataset.formId);
    const title = document.getElementById(configEl.dataset.titleId);
    const submitBtn = document.getElementById('btnSubmit');
    const idField = document.getElementById(configEl.dataset.idField);
    const tanggalField = document.getElementById('tanggal');

    const nisInput = document.getElementById('nis_santri');
    const idSantriField = document.getElementById('id_santri');
    const santriInfo = document.getElementById('santri-info');
    const datalist = document.getElementById('list-nis-santri');

    const santriByNis = new Map();
    const santriById = new Map();
    if (datalist) {
        Array.from(datalist.options).forEach(opt => {
            const info = {
                id: opt.dataset.id || '',
                nis: opt.value || '',
                nama: opt.dataset.nama || '',
                kelas: opt.dataset.kelas || '-'
            };
            if (info.nis) santriByNis.set(info.nis, info);
            if (info.id) santriById.set(info.id, info);
        });
    }

    const syncSantriByNis = () => {
        if (!nisInput || !idSantriField) return;
        const val = nisInput.value.trim();
        const found = santriByNis.get(val);
        if (found) {
            idSantriField.value = found.id;
            if (santriInfo) {
                santriInfo.textContent = `${found.nama} (Kelas ${found.kelas})`;
                santriInfo.className = 'form-text text-success fw-semibold';
            }
        } else {
            idSantriField.value = '';
            if (santriInfo) {
                if (val === '') {
                    santriInfo.textContent = 'Ketik angka NIS untuk melihat daftar santri.';
                    santriInfo.className = 'form-text text-muted';
                } else {
                    santriInfo.textContent = 'NIS tidak ditemukan di database.';
                    santriInfo.className = 'form-text text-danger';
                }
            }
        }
    };

    if (nisInput) {
        nisInput.addEventListener('input', syncSantriByNis);
        nisInput.addEventListener('change', syncSantriByNis);
    }

    const btnTambah = document.getElementById('btnTambah');
    if (btnTambah && form) {
        btnTambah.addEventListener('click', () => {
            form.action = BASEURL + configEl.dataset.tambahUrl;
            if (title) title.textContent = configEl.dataset.tambahTitle;
            if (submitBtn) submitBtn.textContent = 'Tambah Data';
            form.reset();
            if (idField) idField.value = '';
            if (idSantriField) idSantriField.value = '';
            if (nisInput) nisInput.value = '';
            if (tanggalField) tanggalField.value = new Date().toISOString().slice(0, 10);
            syncSantriByNis();
        });
    }

    const fieldMap = JSON.parse(configEl.dataset.fieldMap || '{}');
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!form) return;
            form.action = BASEURL + configEl.dataset.updateUrl;
            if (title) title.textContent = configEl.dataset.editTitle;
            if (submitBtn) submitBtn.textContent = 'Simpan Perubahan';

            if (idField) idField.value = btn.dataset.id || '';
            if (tanggalField) tanggalField.value = btn.dataset.tanggal || '';

            const santriId = btn.dataset.idSantri || '';
            const santri = santriById.get(santriId);
            if (nisInput) {
                nisInput.value = btn.dataset.nis || (santri ? santri.nis : '');
            }
            if (idSantriField) {
                idSantriField.value = santriId;
            }
            syncSantriByNis();

            Object.entries(fieldMap).forEach(([elementId, datasetKey]) => {
                const el = document.getElementById(elementId);
                if (el) el.value = btn.dataset[datasetKey] || '';
            });
        });
    });

    if (form) {
        form.addEventListener('submit', (e) => {
            syncSantriByNis();
            if (!idSantriField || !idSantriField.value) {
                e.preventDefault();
                if (nisInput) nisInput.focus();
            }
        });
    }

    // --- Search, Filter Kelas & Pagination ---
    const search = document.getElementById('tbl-search');
    if (!search) return;
    const filterKelas = document.getElementById('tbl-kelas');
    const limit = document.getElementById('tbl-limit');
    const rows = Array.from(document.querySelectorAll('[data-row]'));
    const records = rows.map(row => ({
        row,
        kelas: (row.dataset.kelas || '').trim(),
        text: Array.from(row.cells).slice(1).map(c => c.textContent).join(' ').toLocaleLowerCase('id')
    }));
    const prev = document.getElementById('tbl-prev');
    const next = document.getElementById('tbl-next');
    let page = 1;

    const render = () => {
        const kw = search.value.trim().toLocaleLowerCase('id');
        const kelasVal = filterKelas ? filterKelas.value.trim() : '';
        const matches = records.filter(r => {
            const matchText = r.text.includes(kw);
            const matchKelas = kelasVal === '' || r.kelas === kelasVal;
            return matchText && matchKelas;
        });
        const size = Number(limit.value);
        const pages = Math.max(1, Math.ceil(matches.length / size));
        page = Math.min(page, pages);
        const start = (page - 1) * size;
        rows.forEach(r => { r.row.hidden = true; });
        matches.slice(start, start + size).forEach((r, i) => {
            r.row.hidden = false;
            r.row.cells[0].textContent = start + i + 1;
        });
        document.getElementById('row-empty').hidden = matches.length > 0;
        const isFiltered = kw !== '' || kelasVal !== '';
        document.getElementById('tbl-summary').textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} data${isFiltered ? ` (total ${rows.length})` : ''}`;
        document.getElementById('tbl-page').textContent = `${page} / ${pages}`;
        document.getElementById('tbl-pagination').hidden = false;
        prev.disabled = page <= 1;
        next.disabled = page >= pages;
    };

    search.addEventListener('input', () => { page = 1; render(); });
    if (filterKelas) filterKelas.addEventListener('change', () => { page = 1; render(); });
    limit.addEventListener('change', () => { page = 1; render(); });
    prev.addEventListener('click', () => { if (page > 1) { page--; render(); } });
    next.addEventListener('click', () => { page++; render(); });
    render();
});
