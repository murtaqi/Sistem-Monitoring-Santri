<?php
$escape = static function ($value) {
    return htmlspecialchars((string)($value ?? '-'), ENT_QUOTES, 'UTF-8');
};

$kelasDipilih = $data['kelas'] ?? '';
$records = $data['records'] ?? [];
$totalSantri = (int)($data['total_santri'] ?? 0);
$totalHafalan = (int)($data['total_hafalan'] ?? 0);
$totalPelanggaranBelumSelesai = (int)($data['total_pelanggaran_belum_selesai'] ?? 0);

$queryParams = $_GET;
unset($queryParams['url'], $queryParams['export']);
$exportQuery = http_build_query($queryParams);
$exportHref = BASEURL . '/Admin/laporanSantri?' . ($exportQuery !== '' ? $exportQuery . '&' : '') . 'export=csv';
?>
<p class="breadcrumb no-print">Laporan <span aria-hidden="true">/</span> Laporan Kondisi Santri</p>
<h1>Laporan Kondisi Santri</h1>
<p class="subtitle">Rekapitulasi capaian hafalan dan status pelanggaran santri per kelas.</p>

<section class="card mb-4 no-print" aria-label="Filter laporan santri">
    <div class="p-4">
        <form method="GET" action="<?= BASEURL ?>/Admin/laporanSantri" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="kelas" class="form-label">Filter Kelas</label>
                <select id="kelas" name="kelas" class="form-select">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($data['daftar_kelas'] ?? [] as $k): ?>
                        <?php $namaKelas = $k['kelas'] ?? ''; ?>
                        <option value="<?= $escape($namaKelas) ?>" <?= $kelasDipilih === $namaKelas ? 'selected' : '' ?>>
                            <?= $escape($namaKelas) ?>
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

<div class="d-flex gap-2 mb-3 no-print">
    <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm">Print</button>
    <a class="btn btn-outline-success btn-sm" href="<?= $exportHref ?>">Export CSV</a>
</div>

<section class="card" aria-labelledby="laporan-santri-title">
    <div class="card-heading">
        <h2 id="laporan-santri-title">Rekap Kondisi Santri</h2>
        <span class="badge"><?= $kelasDipilih !== '' ? 'Kelas ' . $escape($kelasDipilih) : 'Semua Kelas' ?></span>
    </div>
    <div class="table-wrap">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th scope="col" class="number">No</th>
                    <th scope="col">NIS</th>
                    <th scope="col">Nama Santri</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Target Juz</th>
                    <th scope="col">Total Hafalan</th>
                    <th scope="col">Rata-rata Nilai</th>
                    <th scope="col">Pelanggaran (Belum Selesai)</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr>
                        <td colspan="9" class="empty">
                            <strong>Tidak ada data santri</strong>
                            Belum ada data santri pada filter kelas yang dipilih.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($records as $index => $row): ?>
                        <?php
                        $belumSelesai = (int)($row['pelanggaran_belum_selesai'] ?? 0);
                        $perluPerhatian = $belumSelesai > 0;
                        ?>
                        <tr class="<?= $perluPerhatian ? 'table-danger' : '' ?>">
                            <td class="number"><?= $index + 1 ?></td>
                            <td class="number"><?= $escape($row['nis'] ?? '-') ?></td>
                            <td class="admin-name"><?= $escape($row['nama_santri'] ?? '-') ?></td>
                            <td><?= $escape($row['kelas'] ?? '-') ?></td>
                            <td class="number"><?= (int)($row['target_juz'] ?? 0) ?> Juz</td>
                            <td class="number"><?= (int)($row['total_hafalan'] ?? 0) ?> Setoran</td>
                            <td class="number"><?= $escape($row['rata_nilai'] ?? 0) ?></td>
                            <td class="number"><?= (int)($row['total_pelanggaran'] ?? 0) ?> (<?= $belumSelesai ?>)</td>
                            <td>
                                <span class="badge <?= $perluPerhatian ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $perluPerhatian ? 'Perlu Perhatian' : 'Baik' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="5" class="text-end">Total Rekapitulasi (<?= $totalSantri ?> Santri)</td>
                    <td class="number"><?= $totalHafalan ?> Setoran</td>
                    <td></td>
                    <td colspan="2"><?= $totalPelanggaranBelumSelesai ?> Pelanggaran Belum Selesai</td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
