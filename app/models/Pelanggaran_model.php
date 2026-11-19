<?php

    class Pelanggaran_model {
        private $table = 'pelanggaran';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll(){
            $this->db->query('SELECT santri.*, pelanggaran.* FROM ' . $this->table. ' JOIN santri ON santri.id_santri = pelanggaran.id_santri');
            return $this->db->resultSet();
        }
    }