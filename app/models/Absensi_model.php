<?php

    class Absensi_model {
        private $table = 'absensi';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll(){
            $this->db->query('SELECT santri.*, absensi.* FROM ' . $this->table.' JOIN santri ON santri.id_santri = absensi.id_santri');
            return $this->db->resultSet();
        }
    }