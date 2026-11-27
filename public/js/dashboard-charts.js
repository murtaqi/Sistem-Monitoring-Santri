document.addEventListener('DOMContentLoaded', () => {
    const dataEl = document.getElementById('dashboard-chart-data');
    if (!dataEl || typeof Chart === 'undefined') return;

    const payload = JSON.parse(dataEl.dataset.chart || '{}');
    const warnaStatus = { 'Hadir': '#16745c', 'Izin': '#e0a800', 'Sakit': '#e07b39', 'Alpha': '#d1453b' };
    const warnaPelanggaran = { 'Belum Selesai': '#d1453b', 'Selesai': '#16745c' };
    const warnaHafalan = { 'Lancar': '#16745c', 'Kurang Lancar': '#e0a800', 'Belum Lancar': '#d1453b' };
    const garis = '#e5ebe7';
    const teks = '#728079';

    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    Chart.defaults.color = teks;

    const legendaBawah = {
        position: 'bottom',
        labels: { usePointStyle: true, pointStyle: 'circle', padding: 16 }
    };

    const elKehadiran = document.getElementById('chartKehadiran');
    if (elKehadiran) {
        const labels = payload.absensiLabels || [];
        new Chart(elKehadiran, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Jumlah Santri',
                    data: payload.absensiData || [],
                    backgroundColor: labels.map(s => warnaStatus[s] || '#728079'),
                    borderRadius: 6,
                    maxBarThickness: 70
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: legendaBawah,
                    tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' catatan' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: garis } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const elPersentase = document.getElementById('chartPersentase');
    if (elPersentase) {
        const labels = payload.persenLabels || [];
        new Chart(elPersentase, {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    label: 'Persentase',
                    data: payload.persenData || [],
                    backgroundColor: labels.map(s => warnaStatus[s] || '#728079'),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: legendaBawah,
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + '%' } }
                }
            }
        });
    }

    const elPelanggaran = document.getElementById('chartPelanggaran');
    if (elPelanggaran) {
        const labels = payload.pelanggaranLabels || [];
        new Chart(elPelanggaran, {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    label: 'Pelanggaran',
                    data: payload.pelanggaranData || [],
                    backgroundColor: labels.map(s => warnaPelanggaran[s] || '#728079'),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: legendaBawah,
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' kasus' } }
                }
            }
        });
    }

    const elHafalan = document.getElementById('chartHafalan');
    if (elHafalan) {
        const labels = payload.hafalanLabels || [];
        new Chart(elHafalan, {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    label: 'Hafalan',
                    data: payload.hafalanData || [],
                    backgroundColor: labels.map(s => warnaHafalan[s] || '#728079'),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: legendaBawah,
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' setoran' } }
                }
            }
        });
    }

    const elBulanan = document.getElementById('chartTrenBulanan');
    if (elBulanan) {
        new Chart(elBulanan, {
            type: 'line',
            data: {
                labels: payload.bulanLabels || [],
                datasets: [{
                    label: 'Jumlah Catatan Absensi',
                    data: payload.bulanData || [],
                    borderColor: '#16745c',
                    backgroundColor: 'rgba(22, 116, 92, 0.12)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#16745c'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: legendaBawah,
                    tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' catatan' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: garis } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
