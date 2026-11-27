<?php

    class Santri_model {
        private $table = 'santri';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll(){
            $this->db->query('SELECT *FROM ' . $this->table);
            return $this->db->resultSet();
        }

        public function getById($id){
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id_santri = :id_santri LIMIT 1');
            $this->db->bind(':id_santri', (int) $id);
            return $this->db->single();
        }

        public function getSantriByNis($nis){
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE nis = :nis');
            $this->db->bind(':nis', $nis);
            return $this->db->single();
        }

        public function getAdminIdByName($namaLengkap){
            if ($namaLengkap !== '') {
                $this->db->query('SELECT id_user FROM users WHERE nama_lengkap = :nama_lengkap AND role = "admin" LIMIT 1');
                $this->db->bind(':nama_lengkap', $namaLengkap);
                $admin = $this->db->single();
                if ($admin && isset($admin['id_user'])) {
                    return (int) $admin['id_user'];
                }
            }

            $this->db->query('SELECT id_user FROM users WHERE role = "admin" ORDER BY id_user ASC LIMIT 1');
            $fallback = $this->db->single();
            return $fallback && isset($fallback['id_user']) ? (int) $fallback['id_user'] : null;
        }

        public function ortuExists($idOrtu){
            $this->db->query('SELECT id_user FROM users WHERE id_user = :id_ortu AND role = "ortu" LIMIT 1');
            $this->db->bind(':id_ortu', (int) $idOrtu);
            return (bool) $this->db->single();
        }

        public function addSantri($data){
            $targetJuz = isset($data['target_juz']) && $data['target_juz'] !== '' ? (int) $data['target_juz'] : 0;
            $kontak = isset($data['kontak']) && trim((string) $data['kontak']) !== '' ? trim((string) $data['kontak']) : null;

            $this->db->query('INSERT INTO ' . $this->table . ' (id_admin, id_ortu, nis, nama_santri, kelas, target_juz, kontak) VALUES (:id_admin, :id_ortu, :nis, :nama_santri, :kelas, :target_juz, :kontak)');
            $this->db->bind(':id_admin', (int) $data['id_admin']);
            $this->db->bind(':id_ortu', (int) $data['id_ortu']);
            $this->db->bind(':nis', trim((string) $data['nis']));
            $this->db->bind(':nama_santri', trim((string) $data['nama_santri']));
            $this->db->bind(':kelas', trim((string) $data['kelas']));
            $this->db->bind(':target_juz', $targetJuz);
            $this->db->bind(':kontak', $kontak);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function updateSantri($data){
            $targetJuz = isset($data['target_juz']) && $data['target_juz'] !== '' ? (int) $data['target_juz'] : 0;
            $kontak = isset($data['kontak']) && trim((string) $data['kontak']) !== '' ? trim((string) $data['kontak']) : null;

            $this->db->query('UPDATE ' . $this->table . ' SET id_ortu = :id_ortu, nis = :nis, nama_santri = :nama_santri, kelas = :kelas, target_juz = :target_juz, kontak = :kontak WHERE id_santri = :id_santri');
            $this->db->bind(':id_santri', (int) $data['id_santri']);
            $this->db->bind(':id_ortu', (int) $data['id_ortu']);
            $this->db->bind(':nis', trim((string) $data['nis']));
            $this->db->bind(':nama_santri', trim((string) $data['nama_santri']));
            $this->db->bind(':kelas', trim((string) $data['kelas']));
            $this->db->bind(':target_juz', $targetJuz);
            $this->db->bind(':kontak', $kontak);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function deleteSantri($id){
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id_santri = :id');
            $this->db->bind(':id', (int) $id);
            $this->db->execute();
            return $this->db->rowCount();
        }
    }