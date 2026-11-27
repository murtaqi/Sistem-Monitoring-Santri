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

        private function getRedirectUrl(){
            if (!empty($_SERVER['HTTP_REFERER'])) {
                return $_SERVER['HTTP_REFERER'];
            }
            return BASEURL . '/Admin/santri';
        }

        public function tambahSantri(){
            $redirect = $this->getRedirectUrl();

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . $redirect);
                exit;
            }

            $santriModel = $this->model('Santri_model');
            $nis = trim((string) ($_POST['nis'] ?? ''));
            $namaSantri = trim((string) ($_POST['nama_santri'] ?? ''));
            $kelas = trim((string) ($_POST['kelas'] ?? ''));
            $idOrtu = (int) ($_POST['id_ortu'] ?? 0);

            if($nis === '' || $namaSantri === '' || $kelas === '' || $idOrtu <= 0){
                Flasher::setFlash('Data santri tidak lengkap', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            if($santriModel->getSantriByNis($nis)){
                Flasher::setFlash('NIS Sudah Terdaftar', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            if(!$santriModel->ortuExists($idOrtu)){
                Flasher::setFlash('Orang tua tidak valid', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            $idAdmin = $santriModel->getAdminIdByName($_SESSION['user'] ?? '');
            if(!$idAdmin){
                $idAdmin = 1;
            }

            $_POST['id_admin'] = $idAdmin;
            $_POST['id_ortu'] = $idOrtu;

            if($santriModel->addSantri($_POST) > 0){
                Flasher::setFlash('Santri Berhasil Ditambahkan', 'success');
            }else{
                Flasher::setFlash('Santri Gagal Ditambahkan', 'danger');
            }

            header('Location: ' . $redirect);
            exit;
        }

        public function editSantri(){
            $redirect = $this->getRedirectUrl();

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . $redirect);
                exit;
            }

            $santriModel = $this->model('Santri_model');
            $idSantri = (int) ($_POST['id_santri'] ?? 0);
            $nis = trim((string) ($_POST['nis'] ?? ''));
            $namaSantri = trim((string) ($_POST['nama_santri'] ?? ''));
            $kelas = trim((string) ($_POST['kelas'] ?? ''));
            $idOrtu = (int) ($_POST['id_ortu'] ?? 0);

            if($idSantri <= 0 || $nis === '' || $namaSantri === '' || $kelas === ''){
                Flasher::setFlash('Data santri tidak lengkap', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            $existing = $santriModel->getById($idSantri);
            if(!$existing){
                Flasher::setFlash('Data santri tidak ditemukan', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            $checkNis = $santriModel->getSantriByNis($nis);
            if($checkNis && (int)$checkNis['id_santri'] !== $idSantri){
                Flasher::setFlash('NIS Sudah Digunakan Oleh Santri Lain', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            if($idOrtu > 0 && !$santriModel->ortuExists($idOrtu)){
                Flasher::setFlash('Orang tua tidak valid', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            if($idOrtu <= 0 && isset($existing['id_ortu'])){
                $_POST['id_ortu'] = $existing['id_ortu'];
            }

            if($santriModel->updateSantri($_POST) >= 0){
                Flasher::setFlash('Santri Berhasil Diupdate', 'success');
            }else{
                Flasher::setFlash('Santri Gagal Diupdate', 'danger');
            }

            header('Location: ' . $redirect);
            exit;
        }

        public function hapusSantri($id = null){
            $redirect = $this->getRedirectUrl();
            $idSantri = (int) ($id ?? ($_POST['id_santri'] ?? 0));

            if($idSantri <= 0){
                Flasher::setFlash('ID santri tidak valid', 'danger');
                header('Location: ' . $redirect);
                exit;
            }

            if($this->model('Santri_model')->deleteSantri($idSantri) > 0){
                Flasher::setFlash('Santri Berhasil Dihapus', 'success');
            }else{
                Flasher::setFlash('Santri Gagal Dihapus', 'danger');
            }

            header('Location: ' . $redirect);
            exit;
        }

        public function updateSantri(){
            $this->editSantri();
        }

        public function deleteSantri($id = null){
            $this->hapusSantri($id);
        }
    }
