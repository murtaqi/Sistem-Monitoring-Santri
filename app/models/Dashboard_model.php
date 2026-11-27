<?php

    class Dashboard_model {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getGuruIdByName($namaLengkap){
            if ($namaLengkap === '') {
                return 0;
            }
            $this->db->query("SELECT id_user FROM users WHERE nama_lengkap = :nama_lengkap AND role = 'guru' LIMIT 1");
            $this->db->bind(':nama_lengkap', $namaLengkap);
            $row = $this->db->single();
            return $row && isset($row['id_user']) ? (int)$row['id_user'] : 0;
        }

        public function totalSantri(){
            $this->db->query('SELECT COUNT(*) AS jumlah FROM santri');
            $row = $this->db->single();
            return (int)($row['jumlah'] ?? 0);
        }

        public function totalPelanggaran($idGuru = null){
            $sql = 'SELECT COUNT(*) AS jumlah FROM pelanggaran';
            if ($idGuru !== null) {
                $sql .= ' WHERE id_guru = :id_guru';
            }
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            $row = $this->db->single();
            return (int)($row['jumlah'] ?? 0);
        }

        public function pelanggaranBelumSelesai($idGuru = null){
            $sql = "SELECT COUNT(*) AS jumlah FROM pelanggaran WHERE status_sanksi = 'Belum Selesai'";
            if ($idGuru !== null) {
                $sql .= ' AND id_guru = :id_guru';
            }
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            $row = $this->db->single();
            return (int)($row['jumlah'] ?? 0);
        }

        public function absensiPerStatus($idGuru = null){
            $sql = 'SELECT status_kehadiran, COUNT(*) AS jumlah FROM absensi';
            if ($idGuru !== null) {
                $sql .= ' WHERE id_guru = :id_guru';
            }
            $sql .= ' GROUP BY status_kehadiran';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            return $this->db->resultSet();
        }

        public function pelanggaranPerStatus($idGuru = null){
            $sql = 'SELECT status_sanksi, COUNT(*) AS jumlah FROM pelanggaran';
            if ($idGuru !== null) {
                $sql .= ' WHERE id_guru = :id_guru';
            }
            $sql .= ' GROUP BY status_sanksi';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            return $this->db->resultSet();
        }

        public function totalHafalan($idGuru = null){
            $sql = 'SELECT COUNT(*) AS jumlah FROM hafalan';
            if ($idGuru !== null) {
                $sql .= ' WHERE id_guru = :id_guru';
            }
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            $row = $this->db->single();
            return (int)($row['jumlah'] ?? 0);
        }

        public function hafalanPerStatus($idGuru = null){
            $sql = 'SELECT status_setoran, COUNT(*) AS jumlah FROM hafalan';
            if ($idGuru !== null) {
                $sql .= ' WHERE id_guru = :id_guru';
            }
            $sql .= ' GROUP BY status_setoran';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            return $this->db->resultSet();
        }

        public function absensiTerbaru($limit = 10, $idGuru = null){
            $sql = 'SELECT a.tanggal, a.status_kehadiran, a.catatan, s.nis, s.nama_santri, s.kelas
                    FROM absensi a
                    JOIN santri s ON s.id_santri = a.id_santri';
            if ($idGuru !== null) {
                $sql .= ' WHERE a.id_guru = :id_guru';
            }
            $sql .= ' ORDER BY a.tanggal DESC, a.id_absensi DESC LIMIT ' . (int)$limit;
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            return $this->db->resultSet();
        }

        public function absensiPerBulan($idGuru = null){
            $sql = 'SELECT MONTH(tanggal) AS bulan, COUNT(*) AS jumlah FROM absensi WHERE YEAR(tanggal) = YEAR(CURDATE())';
            if ($idGuru !== null) {
                $sql .= ' AND id_guru = :id_guru';
            }
            $sql .= ' GROUP BY MONTH(tanggal) ORDER BY bulan ASC';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int)$idGuru);
            }
            return $this->db->resultSet();
        }
    }
