document.addEventListener('DOMContentLoaded', () => {
    const configEl = document.getElementById('admin-data-config');
    if (!configEl) return;

    const form = document.getElementById(configEl.dataset.formId);
    const title = document.getElementById(configEl.dataset.titleId);
    const submitBtn = document.getElementById(configEl.dataset.submitId || 'btnSubmit');
    const btnTambah = document.getElementById(configEl.dataset.tambahBtnId);
    const idField = document.getElementById(configEl.dataset.idField);

    const isUserMode = configEl.dataset.mode === 'user';
    const roleValue = configEl.dataset.role || '';
    const fieldMap = JSON.parse(configEl.dataset.fieldMap || '{}');

    if (btnTambah && form) {
        btnTambah.addEventListener('click', () => {
            form.action = BASEURL + configEl.dataset.tambahUrl;
            if (title) title.textContent = configEl.dataset.tambahTitle;
            if (submitBtn) submitBtn.textContent = 'Tambah Data';
            form.reset();
            if (idField) idField.value = '';

            if (isUserMode) {
                const roleEl = document.getElementById('role');
                const passEl = document.getElementById('password');
                const confirmEl = document.getElementById('confirm');
                if (roleEl) roleEl.value = roleValue;
                if (passEl) passEl.required = true;
                if (confirmEl) confirmEl.required = true;
            }
        });
    }

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!form) return;
            form.action = BASEURL + configEl.dataset.updateUrl;
            if (title) title.textContent = configEl.dataset.editTitle;
            if (submitBtn) submitBtn.textContent = 'Simpan Perubahan';
            if (idField) idField.value = btn.dataset.id || '';

            Object.entries(fieldMap).forEach(([elementId, datasetKey]) => {
                const el = document.getElementById(elementId);
                if (el) el.value = btn.dataset[datasetKey] || '';
            });

            if (isUserMode) {
                const roleEl = document.getElementById('role');
                const passEl = document.getElementById('password');
                const confirmEl = document.getElementById('confirm');
                if (passEl) {
                    passEl.value = '';
                    passEl.required = false;
                }
                if (confirmEl) {
                    confirmEl.value = '';
                    confirmEl.required = false;
                }
                if (roleEl) roleEl.value = roleValue;
            }
        });
    });

    // --- Search & Pagination ---
    const search = document.getElementById(configEl.dataset.searchId);
    if (!search) return;

    const limit = document.getElementById(configEl.dataset.limitId);
    const rows = Array.from(document.querySelectorAll('[data-row]'));
    const records = rows.map(row => ({
        row,
        text: Array.from(row.cells).slice(1).map(c => c.textContent).join(' ').toLocaleLowerCase('id')
    }));
    const prev = document.getElementById('tbl-prev');
    const next = document.getElementById('tbl-next');
    const emptyRow = document.getElementById('row-empty');
    const emptyTitle = document.getElementById('empty-title');
    const emptyText = document.getElementById('empty-text');
    const summaryEl = document.getElementById('tbl-summary');
    const pageEl = document.getElementById('tbl-page');
    const paginationEl = document.getElementById('tbl-pagination');
    const entityLabel = configEl.dataset.entityLabel || 'data';

    let page = 1;
    const render = () => {
        const kw = search.value.trim().toLocaleLowerCase('id');
        const matches = records.filter(r => r.text.includes(kw));
        const size = Number(limit.value);
        const pages = Math.max(1, Math.ceil(matches.length / size));
        page = Math.min(page, pages);
        const start = (page - 1) * size;

        rows.forEach(r => { r.row.hidden = true; });
        matches.slice(start, start + size).forEach((r, i) => {
            r.row.hidden = false;
            r.row.cells[0].textContent = start + i + 1;
        });

        if (emptyRow) emptyRow.hidden = matches.length > 0;
        if (emptyTitle && configEl.dataset.emptyFoundTitle && configEl.dataset.emptyDefaultTitle) {
            emptyTitle.textContent = rows.length ? configEl.dataset.emptyFoundTitle : configEl.dataset.emptyDefaultTitle;
        }
        if (emptyText && configEl.dataset.emptyFoundText && configEl.dataset.emptyDefaultText) {
            emptyText.textContent = rows.length ? configEl.dataset.emptyFoundText : configEl.dataset.emptyDefaultText;
        }
        if (summaryEl) {
            summaryEl.textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} ${entityLabel}${kw ? ` (total ${rows.length} data)` : ''}`;
        }
        if (pageEl) pageEl.textContent = `${page} / ${pages}`;
        if (paginationEl) paginationEl.hidden = false;
        if (prev) prev.disabled = page <= 1;
        if (next) next.disabled = page >= pages;
    };

    search.addEventListener('input', () => { page = 1; render(); });
    limit.addEventListener('change', () => { page = 1; render(); });
    if (prev) prev.addEventListener('click', () => { if (page > 1) { page--; render(); } });
    if (next) next.addEventListener('click', () => { page++; render(); });
    render();
});
