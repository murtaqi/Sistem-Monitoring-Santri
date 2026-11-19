<?php

    class Guru extends Controller {
        public function __construct(){
            $this->authGuru();
        }

        public function index() {
            $data['judul'] = 'Dashboard Guru';
            $data['guru'] = $this->model('Users_model')->getAllGuru();

            $this->view('templates/header_guru', $data);
            $this->view('guru/index', $data);
            $this->view('templates/footer');
        }

        public function absensi() {
            $data['judul'] = 'Absensi Santri';
            $data['absensi'] = $this->model('Absensi_model')->getAll();

            $this->view('templates/header_guru', $data);
            $this->view('guru/absensi', $data);
            $this->view('templates/footer');
        }

        public function hafalan() {
            $data['judul'] = 'Hafalan Santri';
            $data['hafalan'] = $this->model('Hafalan_model')->getAll();

            $this->view('templates/header_guru', $data);
            $this->view('guru/hafalan', $data);
            $this->view('templates/footer');
        }

        public function pelanggaran() {
            $data['judul'] = 'Pelanggaran Santri';
            $data['pelanggaran'] = $this->model('Pelanggaran_model')->getAll();

            $this->view('templates/header_guru', $data);
            $this->view('guru/pelanggaran', $data);
            $this->view('templates/footer');
        }
    }
