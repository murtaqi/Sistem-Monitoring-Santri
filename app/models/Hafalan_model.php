<?php

    class Hafalan_model {
        private $table = 'hafalan';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll($idGuru = null){
            $sql = 'SELECT santri.*, hafalan.* FROM ' . $this->table . ' JOIN santri ON santri.id_santri = hafalan.id_santri';
            if ($idGuru !== null) {
                $sql .= ' WHERE hafalan.id_guru = :id_guru';
            }
            $sql .= ' ORDER BY hafalan.tanggal DESC, hafalan.id_hafalan DESC';
            $this->db->query($sql);
            if ($idGuru !== null) {
                $this->db->bind(':id_guru', (int) $idGuru);
            }
            return $this->db->resultSet();
        }

        public function getByIdAndGuru($idHafalan, $idGuru){
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id_hafalan = :id_hafalan AND id_guru = :id_guru LIMIT 1');
            $this->db->bind(':id_hafalan', (int) $idHafalan);
            $this->db->bind(':id_guru', (int) $idGuru);
            return $this->db->single();
        }

        public function addHafalan($data){
            $this->db->query('INSERT INTO ' . $this->table . ' (id_santri, id_guru, tanggal, juz, surah, ayat, status_setoran, nilai_kelancaran) VALUES (:id_santri, :id_guru, :tanggal, :juz, :surah, :ayat, :status_setoran, :nilai_kelancaran)');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':id_guru', (int) $data['id_guru']);
            $this->db->bind(':tanggal', $data['tanggal']);
            $this->db->bind(':juz', (int) $data['juz']);
            $this->db->bind(':surah', trim((string) $data['surah']));
            $this->db->bind(':ayat', trim((string) $data['ayat']));
            $this->db->bind(':status_setoran', trim((string) $data['status_setoran']));
            $this->db->bind(':nilai_kelancaran', number_format((float) $data['nilai_kelancaran'], 2, '.', ''));
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function updateHafalan($data){
            $this->db->query('UPDATE ' . $this->table . ' SET id_santri = :id_santri, tanggal = :tanggal, juz = :juz, surah = :surah, ayat = :ayat, status_setoran = :status_setoran, nilai_kelancaran = :nilai_kelancaran WHERE id_hafalan = :id_hafalan AND id_guru = :id_guru');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':tanggal', $data['tanggal']);
            $this->db->bind(':juz', (int) $data['juz']);
            $this->db->bind(':surah', trim((string) $data['surah']));
            $this->db->bind(':ayat', trim((string) $data['ayat']));
            $this->db->bind(':status_setoran', trim((string) $data['status_setoran']));
            $this->db->bind(':nilai_kelancaran', number_format((float) $data['nilai_kelancaran'], 2, '.', ''));
            $this->db->bind(':id_hafalan', (int) $data['id_hafalan']);
            $this->db->bind(':id_guru', (int) $data['id_guru']);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function deleteHafalan($idHafalan, $idGuru){
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id_hafalan = :id_hafalan AND id_guru = :id_guru');
            $this->db->bind(':id_hafalan', (int) $idHafalan);
            $this->db->bind(':id_guru', (int) $idGuru);
            $this->db->execute();
            return $this->db->rowCount();
        }
    }