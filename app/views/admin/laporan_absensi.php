<?php
$escape = static function ($value) {
    return htmlspecialchars((string)($value ?? '-'), ENT_QUOTES, 'UTF-8');
};

$tglAwal = $data['tgl_awal'] ?? '';
$tglAkhir = $data['tgl_akhir'] ?? '';
$idGuru = $data['id_guru'] ?? '';
$records = $data['records'] ?? [];
$rekap = $data['rekap'] ?? ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];
$grandTotal = (int)($data['grand_total'] ?? 0);

$queryParams = $_GET;
unset($queryParams['url'], $queryParams['export']);
$exportQuery = http_build_query($queryParams);
?>
<p class="breadcrumb no-print">Laporan <span aria-hidden="true">/</span> Laporan Absensi Santri</p>
<h1>Laporan Absensi Santri</h1>
<p class="subtitle">Filter dan rekapitulasi catatan kehadiran santri berdasarkan periode tanggal dan guru pencatat.</p>

<section class="card mb-4 no-print" aria-label="Filter laporan absensi">
    <div class="p-4">
        <form method="GET" action="<?= BASEURL ?>/Admin/laporanAbsensi" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                <input type="date" id="tgl_awal" name="tgl_awal" class="form-control" value="<?= $escape($tglAwal) ?>" required>
            </div>
            <div class="col-md-3">
                <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" value="<?= $escape($tglAkhir) ?>" required>
            </div>
            <div class="col-md-4">
                <label for="id_guru" class="form-label">Guru Pencatat</label>
                <select id="id_guru" name="id_guru" class="form-select">
                    <option value="">Semua Guru</option>
                    <?php foreach ($data['guru'] ?? [] as $g): ?>
                        <option value="<?= $escape($g['id_user'] ?? '') ?>" <?= (string)$idGuru === (string)($g['id_user'] ?? '') ? 'selected' : '' ?>>
                            <?= $escape($g['nama_lengkap'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Tampilkan</button>
            </div>
        </form>
    </div>
</section>

<?php if (!empty($records)): ?>
    <div class="d-flex gap-2 mb-3 no-print">
        <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm">Print</button>
        <a class="btn btn-outline-success btn-sm" href="<?= BASEURL ?>/Admin/laporanAbsensi?<?= $exportQuery ?>&export=csv">Export CSV</a>
    </div>

    <section class="card" aria-labelledby="laporan-absensi-title">
        <div class="card-heading">
            <h2 id="laporan-absensi-title">Hasil Laporan Absensi</h2>
            <span class="badge"><?= date('d-m-Y', strtotime($tglAwal)) ?> s/d <?= date('d-m-Y', strtotime($tglAkhir)) ?></span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="number">No</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama Santri</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Guru Pencatat</th>
                        <th scope="col">Status</th>
                        <th scope="col">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $index => $row): ?>
                        <tr>
                            <td class="number"><?= $index + 1 ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td class="number"><?= $escape($row['nis'] ?? '-') ?></td>
                            <td class="admin-name"><?= $escape($row['nama_santri'] ?? '-') ?></td>
                            <td><?= $escape($row['kelas'] ?? '-') ?></td>
                            <td><?= $escape($row['nama_guru'] ?? '-') ?></td>
                            <td><?= $escape($row['status_kehadiran'] ?? '-') ?></td>
                            <td><?= $escape($row['catatan'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="6" class="text-end">Rekapitulasi (Hadir: <?= $rekap['Hadir'] ?>, Izin: <?= $rekap['Izin'] ?>, Sakit: <?= $rekap['Sakit'] ?>, Alpha: <?= $rekap['Alpha'] ?>) — Grand Total</td>
                        <td colspan="2"><?= $grandTotal ?> Catatan</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
<?php elseif ($tglAwal !== '' && $tglAkhir !== ''): ?>
    <div class="alert alert-warning">
        Tidak ada data absensi pada rentang tanggal <?= $escape(date('d-m-Y', strtotime($tglAwal))) ?> s/d <?= $escape(date('d-m-Y', strtotime($tglAkhir))) ?>.
    </div>
<?php else: ?>
    <div class="alert alert-info no-print">
        Silakan pilih <strong>Tanggal Awal</strong> dan <strong>Tanggal Akhir</strong>, lalu klik <strong>Tampilkan</strong> untuk melihat laporan absensi.
    </div>
<?php endif; ?>
