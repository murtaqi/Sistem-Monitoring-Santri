document.addEventListener('DOMContentLoaded', () => {
    const configEl = document.getElementById('table-search-config');
    if (!configEl) return;

    const search = document.getElementById(configEl.dataset.searchId);
    if (!search) return;

    const limit = document.getElementById(configEl.dataset.limitId);
    const rows = Array.from(document.querySelectorAll(configEl.dataset.rowSelector));
    const records = rows.map(row => ({
        row,
        text: Array.from(row.cells).slice(1).map(cell => cell.textContent).join(' ').toLocaleLowerCase('id')
    }));
    const previous = document.getElementById(configEl.dataset.prevId);
    const next = document.getElementById(configEl.dataset.nextId);
    const emptyRow = document.getElementById(configEl.dataset.emptyId);
    const emptyTitle = document.getElementById(configEl.dataset.emptyTitleId);
    const emptyText = document.getElementById(configEl.dataset.emptyTextId);
    const summaryEl = document.getElementById(configEl.dataset.summaryId);
    const pageEl = document.getElementById(configEl.dataset.pageId);
    const paginationEl = document.getElementById(configEl.dataset.paginationId);
    const label = configEl.dataset.entityLabel || 'data';

    let page = 1;
    const render = () => {
        const keyword = search.value.trim().toLocaleLowerCase('id');
        const matches = records.filter(record => record.text.includes(keyword));
        const size = Number(limit.value);
        const pages = Math.max(1, Math.ceil(matches.length / size));
        page = Math.min(page, pages);
        const start = (page - 1) * size;

        rows.forEach(row => { row.hidden = true; });
        matches.slice(start, start + size).forEach((record, index) => {
            record.row.hidden = false;
            record.row.cells[0].textContent = start + index + 1;
        });

        if (emptyRow) emptyRow.hidden = matches.length > 0;
        if (emptyTitle) emptyTitle.textContent = rows.length ? `Data ${label} tidak ditemukan` : `Belum ada data ${label}`;
        if (emptyText) emptyText.textContent = rows.length ? 'Coba gunakan kata kunci yang lain.' : `Data ${label} akan ditampilkan di sini setelah tersedia.`;
        if (summaryEl) {
            summaryEl.textContent = `Menampilkan ${matches.length ? start + 1 : 0}–${Math.min(start + size, matches.length)} dari ${matches.length} ${label}${keyword ? ` (total ${rows.length} data)` : ''}`;
        }
        if (pageEl) pageEl.textContent = `${page} / ${pages}`;
        if (paginationEl) paginationEl.hidden = false;
        if (previous) previous.disabled = page <= 1;
        if (next) next.disabled = page >= pages;
    };

    search.addEventListener('input', () => { page = 1; render(); });
    limit.addEventListener('change', () => { page = 1; render(); });
    if (previous) previous.addEventListener('click', () => { if (page > 1) { page--; render(); } });
    if (next) next.addEventListener('click', () => { page++; render(); });
    render();
});
