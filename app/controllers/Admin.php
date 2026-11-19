<?php

    class Admin extends Controller {
        public function __construct(){
            $this->authAdmin();
        }
        public function index() {
            $data['judul'] = 'Dashboard Admin';
            $data['admin'] = $this->model('Users_model')->getAllAdmin();
            $data['guru'] = $this->model('Users_model')->getAllGuru();
            $data['ortu'] = $this->model('Users_model')->getAllOrtu();
            $data['santri'] = $this->model('Santri_model')->getAll();

            $this->view('templates/header_admin', $data);
            $this->view('admin/index', $data);
            $this->view('templates/footer');
        }

        public function admin() {
            $data['judul'] = 'Data Admin';
            $data['admin'] = $this->model('Users_model')->getAllAdmin();

            $this->view('templates/header_admin', $data);
            $this->view('admin/admin', $data);
            $this->view('templates/footer');
        }

        public function guru() {
            $data['judul'] = 'Data Guru';
            $data['records'] = $this->model('Users_model')->getAllGuru();

            $this->view('templates/header_admin', $data);
            $this->view('admin/guru', $data);
            $this->view('templates/footer');
        }

        public function ortu() {
            $data['judul'] = 'Data Orang Tua';
            $data['records'] = $this->model('Users_model')->getAllOrtu();

            $this->view('templates/header_admin', $data);
            $this->view('admin/ortu', $data);
            $this->view('templates/footer');
        }

        public function santri() {
            $data['judul'] = 'Data Santri';
            $data['records'] = $this->model('Santri_model')->getAll();

            $this->view('templates/header_admin', $data);
            $this->view('admin/santri', $data);
            $this->view('templates/footer');
        }

        public function tambahUser(){
            $username = $this->model('Users_model')->getUserByUsername($_POST['username']);
            if($username){
                Flasher::setFlash('Username Sudah Terdaftar', 'danger');
                header('Location: '.BASEURL.'/Admin');
                exit;
            }

            if($_POST['password'] !== $_POST['confirm']){
                Flasher::setFlash('Konfirmasi Password Salah', 'danger');
                header('Location: '.BASEURL.'/Admin');
                exit;
            }

            if($this->model('Users_model')->addUser($_POST) > 0){
                Flasher::setFlash('User Berhasil Ditambahkan', 'success');
                header('Location: '.BASEURL.'/Admin');
                exit;
            }else{
                Flasher::setFlash('User Gagal Ditambahkan', 'danger');
                header('Location: '.BASEURL.'/Admin');
                exit;
            }
        }
    }
