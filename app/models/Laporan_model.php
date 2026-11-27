<?php

    class Laporan_model {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getLaporanAbsensi($tglAwal, $tglAkhir, $idGuru = null){
            $sql = 'SELECT a.id_absensi, a.tanggal, a.status_kehadiran, a.catatan,
                           s.nis, s.nama_santri, s.kelas,
                           u.nama_lengkap AS nama_guru
                    FROM absensi a
                    JOIN santri s ON s.id_santri = a.id_santri
                    JOIN users u ON u.id_user = a.id_guru
                    WHERE DATE(a.tanggal) BETWEEN :tgl_awal AND :tgl_akhir';

            if ($idGuru !== null && $idGuru > 0) {
                $sql .= ' AND a.id_guru = :id_guru';
            }

            $sql .= ' ORDER BY a.tanggal ASC, a.id_absensi ASC';

            $this->db->query($sql);
            $this->db->bind(':tgl_awal', $tglAwal);
            $this->db->bind(':tgl_akhir', $tglAkhir);
            if ($idGuru !== null && $idGuru > 0) {
                $this->db->bind(':id_guru', (int) $idGuru);
            }

            return $this->db->resultSet();
        }

        public function getDaftarKelas(){
            $this->db->query('SELECT DISTINCT kelas FROM santri WHERE kelas IS NOT NULL AND kelas != "" ORDER BY kelas ASC');
            return $this->db->resultSet();
        }

        public function getLaporanKondisiSantri($kelas = ''){
            $sql = 'SELECT s.id_santri, s.nis, s.nama_santri, s.kelas, s.target_juz,
                           (SELECT COUNT(*) FROM hafalan h WHERE h.id_santri = s.id_santri) AS total_hafalan,
                           (SELECT COALESCE(ROUND(AVG(h.nilai_kelancaran), 1), 0) FROM hafalan h WHERE h.id_santri = s.id_santri) AS rata_nilai,
                           (SELECT COUNT(*) FROM pelanggaran p WHERE p.id_santri = s.id_santri) AS total_pelanggaran,
                           (SELECT COUNT(*) FROM pelanggaran p WHERE p.id_santri = s.id_santri AND p.status_sanksi = "Belum Selesai") AS pelanggaran_belum_selesai
                    FROM santri s';

            if ($kelas !== '') {
                $sql .= ' WHERE s.kelas = :kelas';
            }

            $sql .= ' ORDER BY pelanggaran_belum_selesai DESC, s.kelas ASC, s.nama_santri ASC';

            $this->db->query($sql);
            if ($kelas !== '') {
                $this->db->bind(':kelas', $kelas);
            }

            return $this->db->resultSet();
        }
    }
