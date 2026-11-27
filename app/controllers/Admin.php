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

        public function laporanAbsensi() {
            $tglAwal = trim((string) ($_GET['tgl_awal'] ?? ''));
            $tglAkhir = trim((string) ($_GET['tgl_akhir'] ?? ''));
            $idGuru = isset($_GET['id_guru']) && $_GET['id_guru'] !== '' ? (int) $_GET['id_guru'] : null;

            $laporanModel = $this->model('Laporan_model');
            $records = [];
            $rekap = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpha' => 0];

            if ($tglAwal !== '' && $tglAkhir !== '') {
                $records = $laporanModel->getLaporanAbsensi($tglAwal, $tglAkhir, $idGuru);
                foreach ($records as $row) {
                    $status = $row['status_kehadiran'] ?? '';
                    if (isset($rekap[$status])) {
                        $rekap[$status]++;
                    }
                }
            }

            if (isset($_GET['export']) && $_GET['export'] === 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=laporan_absensi.csv');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Tanggal', 'NIS', 'Nama Santri', 'Kelas', 'Guru Pencatat', 'Status Kehadiran', 'Catatan']);
                foreach ($records as $row) {
                    fputcsv($out, [
                        $row['tanggal'] ?? '',
                        $row['nis'] ?? '',
                        $row['nama_santri'] ?? '',
                        $row['kelas'] ?? '',
                        $row['nama_guru'] ?? '',
                        $row['status_kehadiran'] ?? '',
                        $row['catatan'] ?? '-',
                    ]);
                }
                fclose($out);
                exit;
            }

            $data['judul'] = 'Laporan Absensi Santri';
            $data['guru'] = $this->model('Users_model')->getAllGuru();
            $data['tgl_awal'] = $tglAwal;
            $data['tgl_akhir'] = $tglAkhir;
            $data['id_guru'] = $idGuru;
            $data['records'] = $records;
            $data['rekap'] = $rekap;
            $data['grand_total'] = count($records);

            $this->view('templates/header_admin', $data);
            $this->view('admin/laporan_absensi', $data);
            $this->view('templates/footer');
        }

        public function laporanSantri() {
            $kelas = trim((string) ($_GET['kelas'] ?? ''));
            $laporanModel = $this->model('Laporan_model');
            $records = $laporanModel->getLaporanKondisiSantri($kelas);

            $totalSantri = count($records);
            $totalHafalan = 0;
            $totalPelanggaranBelumSelesai = 0;
            foreach ($records as $row) {
                $totalHafalan += (int) ($row['total_hafalan'] ?? 0);
                $totalPelanggaranBelumSelesai += (int) ($row['pelanggaran_belum_selesai'] ?? 0);
            }

            if (isset($_GET['export']) && $_GET['export'] === 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=laporan_kondisi_santri.csv');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['NIS', 'Nama Santri', 'Kelas', 'Target Juz', 'Total Hafalan', 'Rata-rata Nilai', 'Total Pelanggaran', 'Pelanggaran Belum Selesai', 'Status']);
                foreach ($records as $row) {
                    $belumSelesai = (int) ($row['pelanggaran_belum_selesai'] ?? 0);
                    fputcsv($out, [
                        $row['nis'] ?? '',
                        $row['nama_santri'] ?? '',
                        $row['kelas'] ?? '',
                        $row['target_juz'] ?? 0,
                        $row['total_hafalan'] ?? 0,
                        $row['rata_nilai'] ?? 0,
                        $row['total_pelanggaran'] ?? 0,
                        $belumSelesai,
                        $belumSelesai > 0 ? 'Perlu Perhatian' : 'Baik',
                    ]);
                }
                fclose($out);
                exit;
            }

            $data['judul'] = 'Laporan Kondisi Santri';
            $data['daftar_kelas'] = $laporanModel->getDaftarKelas();
            $data['kelas'] = $kelas;
            $data['records'] = $records;
            $data['total_santri'] = $totalSantri;
            $data['total_hafalan'] = $totalHafalan;
            $data['total_pelanggaran_belum_selesai'] = $totalPelanggaranBelumSelesai;

            $this->view('templates/header_admin', $data);
            $this->view('admin/laporan_santri', $data);
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

        public function hapusUser($id, $role = ''){
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
