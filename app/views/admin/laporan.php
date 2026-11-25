<?php
$escape = static function ($value) {
    return htmlspecialchars((string)($value ?? '-'), ENT_QUOTES, 'UTF-8');
};

$statusAbsensi = ['Hadir' => '#16745c', 'Izin' => '#e0a800', 'Sakit' => '#e07b39', 'Alpha' => '#d1453b'];
$urutanStatus  = ['Hadir', 'Izin', 'Sakit', 'Alpha'];

$absensiCount = [];
foreach ($data['absensiPerStatus'] as $row) {
    $absensiCount[$row['status_kehadiran']] = (int)$row['jumlah'];
}
$totalAbsensi = array_sum($absensiCount);

$labelsAbsensi = [];
$dataAbsensi   = [];
$labelsPersen  = [];
$dataPersen    = [];
foreach ($urutanStatus as $status) {
    $jumlah = $absensiCount[$status] ?? 0;
    $labelsAbsensi[] = $status;
    $dataAbsensi[]   = $jumlah;
    $labelsPersen[]  = $status;
    $dataPersen[]    = $totalAbsensi > 0 ? round($jumlah / $totalAbsensi * 100, 1) : 0;
}

$labelsPelanggaran = [];
$dataPelanggaran   = [];
foreach ($data['pelanggaranPerStatus'] as $row) {
    $labelsPelanggaran[] = $row['status_sanksi'];
    $dataPelanggaran[]   = (int)$row['jumlah'];
}

$urutanHafalan = ['Lancar', 'Kurang Lancar', 'Belum Lancar'];
$hafalanCount  = [];
foreach ($data['hafalanPerStatus'] ?? [] as $row) {
    $hafalanCount[$row['status_setoran']] = (int)$row['jumlah'];
}
$totalHafalan  = (int)($data['totalHafalan'] ?? array_sum($hafalanCount));
$labelsHafalan = [];
$dataHafalan   = [];
foreach ($urutanHafalan as $status) {
    $labelsHafalan[] = $status;
    $dataHafalan[]   = $hafalanCount[$status] ?? 0;
}
?>
<p class="breadcrumb">Dashboard <span aria-hidden="true">/</span> Sistem Informasi</p>
<h1>Dashboard Sistem Informasi</h1>
<p class="subtitle"><?= $escape($data['subtitle'] ?? 'Ringkasan data santri, kehadiran, dan pelanggaran.') ?></p>

<section class="kpi-grid" aria-label="Ringkasan angka">
    <div class="kpi">
        <span class="kpi-label">Total Santri Aktif</span>
        <strong class="kpi-value"><?= (int)$data['totalSantri'] ?></strong>
        <span class="kpi-note">terdaftar di tabel santri</span>
    </div>
    <div class="kpi kpi-hadir">
        <span class="kpi-label">Santri Hadir</span>
        <strong class="kpi-value"><?= $absensiCount['Hadir'] ?? 0 ?></strong>
        <span class="kpi-note">dari <?= $totalAbsensi ?> catatan kehadiran</span>
    </div>
    <div class="kpi kpi-pelanggaran">
        <span class="kpi-label">Total Pelanggaran</span>
        <strong class="kpi-value"><?= (int)$data['totalPelanggaran'] ?></strong>
        <span class="kpi-note"><?= (int)$data['pelanggaranBelumSelesai'] ?> belum selesai</span>
    </div>
    <div class="kpi kpi-sakit">
        <span class="kpi-label">Tidak Hadir</span>
        <strong class="kpi-value"><?= ($absensiCount['Izin'] ?? 0) + ($absensiCount['Sakit'] ?? 0) + ($absensiCount['Alpha'] ?? 0) ?></strong>
        <span class="kpi-note">izin, sakit, dan alpha</span>
    </div>
</section>

<div class="chart-grid">
    <section class="card" aria-labelledby="chart-kehadiran-title">
        <div class="card-heading">
            <h2 id="chart-kehadiran-title">Statistik Kehadiran</h2>
            <span class="badge"><?= $totalAbsensi ?> catatan</span>
        </div>
        <div class="chart-body">
            <canvas id="chartKehadiran" role="img" aria-label="Grafik batang jumlah kehadiran menurut status"></canvas>
        </div>
    </section>

    <section class="card" aria-labelledby="chart-persen-title">
        <div class="card-heading">
            <h2 id="chart-persen-title">Persentase Kehadiran</h2>
            <span class="badge"><?= $totalAbsensi ?> catatan</span>
        </div>
        <div class="chart-body">
            <canvas id="chartPersentase" role="img" aria-label="Grafik lingkaran persentase kehadiran menurut status"></canvas>
        </div>
    </section>

    <section class="card" aria-labelledby="chart-pelanggaran-title">
        <div class="card-heading">
            <h2 id="chart-pelanggaran-title">Pelanggaran Santri</h2>
            <span class="badge"><?= (int)$data['totalPelanggaran'] ?> kasus</span>
        </div>
        <div class="chart-body">
            <canvas id="chartPelanggaran" role="img" aria-label="Grafik lingkaran pelanggaran menurut status sanksi"></canvas>
        </div>
    </section>

    <section class="card" aria-labelledby="chart-hafalan-title">
        <div class="card-heading">
            <h2 id="chart-hafalan-title">Hafalan Santri</h2>
            <span class="badge"><?= $totalHafalan ?> setoran</span>
        </div>
        <div class="chart-body">
            <canvas id="chartHafalan" role="img" aria-label="Grafik lingkaran hafalan santri menurut status setoran"></canvas>
        </div>
    </section>
</div>

<section class="card" aria-labelledby="table-absensi-title">
    <div class="card-heading">
        <h2 id="table-absensi-title">Absensi Terbaru</h2>
        <span class="badge">10 catatan terakhir</span>
    </div>
    <div class="table-wrap">
        <table>
            <caption>Daftar absensi santri terbaru</caption>
            <thead>
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">NIS</th>
                    <th scope="col">Nama Santri</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Status</th>
                    <th scope="col">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['absensiTerbaru'])): ?>
                    <tr>
                        <td colspan="6" class="empty">
                            <strong>Belum ada data absensi</strong>
                            Catatan kehadiran santri akan muncul di sini setelah guru menginput absensi.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['absensiTerbaru'] as $row): ?>
                        <?php
                        $status = $row['status_kehadiran'] ?? '-';
                        $warna  = $statusAbsensi[$status] ?? '#728079';
                        ?>
                        <tr>
                            <td><?= $escape($row['tanggal'] ?? '-') ?></td>
                            <td class="number"><?= $escape($row['nis'] ?? '-') ?></td>
                            <td class="admin-name"><?= $escape($row['nama_santri'] ?? '-') ?></td>
                            <td><?= $escape($row['kelas'] ?? '-') ?></td>
                            <td>
                                <span class="status-chip" style="background:<?= $warna ?>1a;color:<?= $warna ?>;border-color:<?= $warna ?>40;">
                                    <?= $escape($status) ?>
                                </span>
                            </td>
                            <td><?= $escape($row['catatan'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script src="<?= BASEURL ?>/js/chart.umd.min.js"></script>
<script>
(function () {
    const absensiLabels = <?= json_encode($labelsAbsensi) ?>;
    const absensiData   = <?= json_encode($dataAbsensi) ?>;
    const persenLabels  = <?= json_encode($labelsPersen) ?>;
    const persenData    = <?= json_encode($dataPersen) ?>;
    const pelanggaranLabels = <?= json_encode($labelsPelanggaran) ?>;
    const pelanggaranData   = <?= json_encode($dataPelanggaran) ?>;
    const hafalanLabels     = <?= json_encode($labelsHafalan) ?>;
    const hafalanData       = <?= json_encode($dataHafalan) ?>;

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

    new Chart(document.getElementById('chartKehadiran'), {
        type: 'bar',
        data: {
            labels: absensiLabels,
            datasets: [{
                label: 'Jumlah Santri',
                data: absensiData,
                backgroundColor: absensiLabels.map(s => warnaStatus[s] || '#728079'),
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

    new Chart(document.getElementById('chartPersentase'), {
        type: 'pie',
        data: {
            labels: persenLabels,
            datasets: [{
                label: 'Persentase',
                data: persenData,
                backgroundColor: persenLabels.map(s => warnaStatus[s] || '#728079'),
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

    new Chart(document.getElementById('chartPelanggaran'), {
        type: 'pie',
        data: {
            labels: pelanggaranLabels,
            datasets: [{
                label: 'Pelanggaran',
                data: pelanggaranData,
                backgroundColor: pelanggaranLabels.map(s => warnaPelanggaran[s] || '#728079'),
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

    new Chart(document.getElementById('chartHafalan'), {
        type: 'pie',
        data: {
            labels: hafalanLabels,
            datasets: [{
                label: 'Hafalan',
                data: hafalanData,
                backgroundColor: hafalanLabels.map(s => warnaHafalan[s] || '#728079'),
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
})();
</script>
