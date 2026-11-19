<?php

    class Santri extends Controller {
        public function __construct(){
            $this->authAdmin();
        }
        public function index() {
            $data['judul'] = 'Dashboard Santri';
            $data['santri'] = $this->model('Santri_model')->getAll();

            $this->view('templates/header', $data);
            $this->view('santri/index', $data);
            $this->view('templates/footer');
        }
    }
