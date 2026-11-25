<?php

    class Pelanggaran_model {
        private $table = 'pelanggaran';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll($idGuru = null){
            $sql = 'SELECT santri.*, pelanggaran.* FROM ' . $this->table . ' JOIN santri ON santri.id_santri = pelanggaran.id_santri';
            if ($idGuru !== null) {
                $sql .= ' WHERE pelanggaran.id_guru = :id_guru';
            }
            $sql .= ' ORDER BY pelanggaran.tanggal DESC, pelanggaran.id_pelanggaran DESC';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int) $idGuru);
            }
            return $this->db->resultSet();
        }

        public function getByIdAndGuru($idPelanggaran, $idGuru){
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id_pelanggaran = :id_pelanggaran AND id_guru = :id_guru LIMIT 1');
            $this->db->bind(':id_pelanggaran', (int) $idPelanggaran);
            $this->db->bind(':id_guru', (int) $idGuru);
            return $this->db->single();
        }

        public function addPelanggaran($data){
            $this->db->query('INSERT INTO ' . $this->table . ' (id_santri, id_guru, tanggal, jenis_pelanggaran, sanksi, status_sanksi) VALUES (:id_santri, :id_guru, :tanggal, :jenis_pelanggaran, :sanksi, :status_sanksi)');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':id_guru', (int) $data['id_guru']);
            $this->db->bind(':tanggal', $data['tanggal']);
            $this->db->bind(':jenis_pelanggaran', trim((string) $data['jenis_pelanggaran']));
            $this->db->bind(':sanksi', trim((string) $data['sanksi']));
            $this->db->bind(':status_sanksi', trim((string) $data['status_sanksi']));
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function updatePelanggaran($data){
            $this->db->query('UPDATE ' . $this->table . ' SET id_santri = :id_santri, tanggal = :tanggal, jenis_pelanggaran = :jenis_pelanggaran, sanksi = :sanksi, status_sanksi = :status_sanksi WHERE id_pelanggaran = :id_pelanggaran AND id_guru = :id_guru');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':tanggal', $data['tanggal']);
            $this->db->bind(':jenis_pelanggaran', trim((string) $data['jenis_pelanggaran']));
            $this->db->bind(':sanksi', trim((string) $data['sanksi']));
            $this->db->bind(':status_sanksi', trim((string) $data['status_sanksi']));
            $this->db->bind(':id_pelanggaran', (int) $data['id_pelanggaran']);
            $this->db->bind(':id_guru', (int) $data['id_guru']);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function deletePelanggaran($idPelanggaran, $idGuru){
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id_pelanggaran = :id_pelanggaran AND id_guru = :id_guru');
            $this->db->bind(':id_pelanggaran', (int) $idPelanggaran);
            $this->db->bind(':id_guru', (int) $idGuru);
            $this->db->execute();
            return $this->db->rowCount();
        }
    }