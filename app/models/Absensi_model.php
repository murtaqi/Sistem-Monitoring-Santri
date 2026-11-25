<?php

    class Absensi_model {
        private $table = 'absensi';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll($idGuru = null){
            $sql = 'SELECT santri.*, absensi.* FROM ' . $this->table . ' JOIN santri ON santri.id_santri = absensi.id_santri';
            if ($idGuru !== null) {
                $sql .= ' WHERE absensi.id_guru = :id_guru';
            }
            $sql .= ' ORDER BY absensi.tanggal DESC, absensi.id_absensi DESC';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int) $idGuru);
            }
            return $this->db->resultSet();
        }

        public function getByIdAndGuru($idAbsensi, $idGuru){
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id_absensi = :id_absensi AND id_guru = :id_guru LIMIT 1');
            $this->db->bind(':id_absensi', (int) $idAbsensi);
            $this->db->bind(':id_guru', (int) $idGuru);
            return $this->db->single();
        }

        public function addAbsensi($data){
            $catatan = isset($data['catatan']) && trim((string) $data['catatan']) !== '' ? trim((string) $data['catatan']) : null;
            $this->db->query('INSERT INTO ' . $this->table . ' (id_santri, id_guru, tanggal, status_kehadiran, catatan) VALUES (:id_santri, :id_guru, :tanggal, :status_kehadiran, :catatan)');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':id_guru', (int) $data['id_guru']);
            $this->db->bind(':tanggal', $data['tanggal']);
            $this->db->bind(':status_kehadiran', $data['status_kehadiran']);
            $this->db->bind(':catatan', $catatan);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function updateAbsensi($data){
            $catatan = isset($data['catatan']) && trim((string) $data['catatan']) !== '' ? trim((string) $data['catatan']) : null;
            $this->db->query('UPDATE ' . $this->table . ' SET id_santri = :id_santri, tanggal = :tanggal, status_kehadiran = :status_kehadiran, catatan = :catatan WHERE id_absensi = :id_absensi AND id_guru = :id_guru');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':tanggal', $data['tanggal']);
            $this->db->bind(':status_kehadiran', $data['status_kehadiran']);
            $this->db->bind(':catatan', $catatan);
            $this->db->bind(':id_absensi', (int) $data['id_absensi']);
            $this->db->bind(':id_guru', (int) $data['id_guru']);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function deleteAbsensi($idAbsensi, $idGuru){
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id_absensi = :id_absensi AND id_guru = :id_guru');
            $this->db->bind(':id_absensi', (int) $idAbsensi);
            $this->db->bind(':id_guru', (int) $idGuru);
            $this->db->execute();
            return $this->db->rowCount();
        }
    }