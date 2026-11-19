<?php

    class Ortu extends Controller {
        public function __construct(){
            $this->authOrtu();
        }
        
        public function index() {
            $data['judul'] = 'Dashboard Ortu';
            $data['ortu'] = $this->model('Users_model')->getAllOrtu();

            $this->view('templates/header_ortu', $data);
            $this->view('ortu/index', $data);
            $this->view('templates/footer');
        }

        public function absensi() {
            $data['judul'] = 'Absensi Anak';
            $data['absensi'] = $this->model('Absensi_model')->getAll();

            $this->view('templates/header_ortu', $data);
            $this->view('ortu/absensi', $data);
            $this->view('templates/footer');
        }

        public function hafalan() {
            $data['judul'] = 'Hafalan Anak';
            $data['hafalan'] = $this->model('Hafalan_model')->getAll();

            $this->view('templates/header_ortu', $data);
            $this->view('ortu/hafalan', $data);
            $this->view('templates/footer');
        }

        public function pelanggaran() {
            $data['judul'] = 'Pelanggaran Anak';
            $data['pelanggaran'] = $this->model('Pelanggaran_model')->getAll();

            $this->view('templates/header_ortu', $data);
            $this->view('ortu/pelanggaran', $data);
            $this->view('templates/footer');
        }
    }
