<?php

    class Hafalan_model {
        private $table = 'hafalan';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAll(){
            $this->db->query('SELECT santri.*, hafalan.* FROM ' . $this->table. ' JOIN santri ON santri.id_santri = hafalan.id_santri');
            return $this->db->resultSet();
        }
    }