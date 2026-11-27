<?php

    class Users_model {
        private $table = 'users';
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getAllAdmin(){
            $this->db->query('SELECT * FROM ' . $this->table.' WHERE role="admin"');
            return $this->db->resultSet();
        }

        public function getAllGuru(){
            $this->db->query('SELECT * FROM ' . $this->table.' WHERE role="guru"');
            return $this->db->resultSet();
        }

        public function getAllOrtu(){
            $this->db->query('SELECT * FROM ' . $this->table.' WHERE role="ortu"');
            return $this->db->resultSet();
        }

        public function getUserByUsername($username){
            $this->db->query('SELECT * FROM '.$this->table.' WHERE username=:username');
            $this->db->bind('username', $username);
            return $this->db->single();
        }

        public function addUser($data){
            $username      = strtolower($data['username']);
            $password      = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->db->query('INSERT INTO '.$this->table.' (nama_lengkap, username, password, role) VALUES (:nama_lengkap, :username, :password, :role)');
            $this->db->bind(':nama_lengkap',   $data['nama_lengkap']);
            $this->db->bind(':username',       $username);
            $this->db->bind(':password',       $password);
            $this->db->bind(':role',           $data['role']);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function deleteUser($id){
            $this->db->query('DELETE FROM '.$this->table.' WHERE id_user=:id');
            $this->db->bind('id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        }

        public function updateUser($data){
            if (!empty($data['password'])) {
                $this->db->query('UPDATE ' . $this->table . ' SET nama_lengkap=:nama_lengkap, username=:username, password=:password, role=:role WHERE id_user=:id');
                $this->db->bind(':password', $data['password']);
            } else {
                $this->db->query('UPDATE ' . $this->table . ' SET nama_lengkap=:nama_lengkap, username=:username, role=:role WHERE id_user=:id');
            }

            $this->db->bind(':nama_lengkap', $data['nama_lengkap']);
            $this->db->bind(':username',     $data['username']);
            $this->db->bind(':role',         $data['role']);     
            $this->db->bind(':id',           $data['id_user']);
            $this->db->execute();
            return $this->db->rowCount();
        }
    }