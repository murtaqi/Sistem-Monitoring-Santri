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
    }