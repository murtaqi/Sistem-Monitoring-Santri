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
            $data['ortu'] = $this->model('Users_model')->getAllOrtu();

            $this->view('templates/header_admin', $data);
            $this->view('admin/santri', $data);
            $this->view('templates/footer');
        }

        public function laporan() {
            $data['judul'] = 'Dashboard Sistem Informasi';
            $dashboard = $this->model('Dashboard_model');
            $data['totalSantri'] = $dashboard->totalSantri();
            $data['totalPelanggaran'] = $dashboard->totalPelanggaran();
            $data['pelanggaranBelumSelesai'] = $dashboard->pelanggaranBelumSelesai();
            $data['absensiPerStatus'] = $dashboard->absensiPerStatus();
            $data['pelanggaranPerStatus'] = $dashboard->pelanggaranPerStatus();
            $data['totalHafalan'] = $dashboard->totalHafalan();
            $data['hafalanPerStatus'] = $dashboard->hafalanPerStatus();
            $data['absensiPerBulan'] = $dashboard->absensiPerBulan();
            $data['absensiTerbaru'] = $dashboard->absensiTerbaru(10);

            $this->view('templates/header_admin', $data);
            $this->view('admin/laporan', $data);
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

        public function editUser(){
            $role = $_POST['role'];
            $redirect = match ($role){
                'admin' => BASEURL.'/Admin/admin',
                'guru' => BASEURL.'/Admin/guru',
                'ortu' => BASEURL.'/Admin/ortu',
                default => BASEURL.'/Admin',     
            };

            if(!empty($_POST['password'])){
                if($_POST['password'] !== $_POST['confirm']){
                    Flasher::setFlash('Konfirmasi Password Tidak Cocok', 'danger');
                    header('Location: '.$redirect);
                    exit;
                }
                $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }else{
                $_POST['password'] = null;
            }

            if($this->model('Users_model')->updateUser($_POST) > 0){
                Flasher::setFlash('User Berhasil diupdate', 'success');
                header('Location: '.$redirect);
                exit;
            }else{
                Flasher::setFlash('User Gagal diupdate', 'danger');
                header('Location: '.$redirect);
                exit;
            }
        }

        public function hapusUser($id){
            $role = $_POST['role'];
            $redirect = match ($role){
                'admin' => BASEURL.'/Admin/admin',
                'guru' => BASEURL.'/Admin/guru',
                'ortu' => BASEURL.'/Admin/ortu',
                default => BASEURL.'/Admin',     
            };

            if($this->model('Users_model')->deleteUser($id) > 0){
                Flasher::setFlash('User Berhasil dihapus', 'success');
                header('Location: '.$redirect);
                exit;
            }else{
                Flasher::setFlash('User Gagal dihapus', 'danger');
                header('Location: '.$redirect);
                exit;
            }
        }
    }
