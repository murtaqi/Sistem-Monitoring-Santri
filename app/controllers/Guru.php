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

        public function laporan() {
            $data['judul'] = 'Dashboard Sistem Informasi';
            $data['subtitle'] = 'Ringkasan data santri, kehadiran, dan pelanggaran yang Anda catat.';
            $dashboard = $this->model('Dashboard_model');
            $idGuru = $dashboard->getGuruIdByName($_SESSION['user'] ?? '');
            $data['totalSantri'] = $dashboard->totalSantri();
            $data['totalPelanggaran'] = $dashboard->totalPelanggaran($idGuru);
            $data['pelanggaranBelumSelesai'] = $dashboard->pelanggaranBelumSelesai($idGuru);
            $data['absensiPerStatus'] = $dashboard->absensiPerStatus($idGuru);
            $data['pelanggaranPerStatus'] = $dashboard->pelanggaranPerStatus($idGuru);
            $data['totalHafalan'] = $dashboard->totalHafalan($idGuru);
            $data['hafalanPerStatus'] = $dashboard->hafalanPerStatus($idGuru);
            $data['absensiPerBulan'] = $dashboard->absensiPerBulan($idGuru);
            $data['absensiTerbaru'] = $dashboard->absensiTerbaru(10, $idGuru);

            $this->view('templates/header_guru', $data);
            $this->view('guru/laporan', $data);
            $this->view('templates/footer');
        }

        private function getLoggedInGuruId() {
            return $this->model('Dashboard_model')->getGuruIdByName($_SESSION['user'] ?? '');
        }

        private function redirectGuru($section, $message = null, $type = 'success') {
            if ($message !== null) {
                Flasher::setFlash($message, $type);
            }
            header('Location: ' . BASEURL . '/Guru/' . $section);
            exit;
        }

        private function validateBaseInput($section) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirectGuru($section);
            }
            $idGuru = $this->getLoggedInGuruId();
            if ($idGuru <= 0) {
                $this->redirectGuru($section, 'Akun guru tidak ditemukan', 'danger');
            }
            $idSantri = (int) ($_POST['id_santri'] ?? 0);
            $tanggal = trim((string) ($_POST['tanggal'] ?? ''));
            if ($idSantri <= 0 || !$this->model('Santri_model')->getById($idSantri)) {
                $this->redirectGuru($section, 'Santri tidak valid', 'danger');
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
                $this->redirectGuru($section, 'Tanggal tidak valid', 'danger');
            }
            return ['id_guru' => $idGuru, 'id_santri' => $idSantri, 'tanggal' => $tanggal];
        }

        public function absensi() {
            $idGuru = $this->getLoggedInGuruId();
            $data['judul'] = 'Absensi Santri';
            $data['absensi'] = $this->model('Absensi_model')->getAll($idGuru);
            $data['santri'] = $this->model('Santri_model')->getAll();

            $this->view('templates/header_guru', $data);
            $this->view('guru/absensi', $data);
            $this->view('templates/footer');
        }

        public function tambahAbsensi() {
            $base = $this->validateBaseInput('absensi');
            $status = trim((string) ($_POST['status_kehadiran'] ?? ''));
            if (!in_array($status, ['Hadir', 'Izin', 'Sakit', 'Alpha'], true)) {
                $this->redirectGuru('absensi', 'Status kehadiran tidak valid', 'danger');
            }
            $payload = $base + [
                'status_kehadiran' => $status,
                'catatan' => $_POST['catatan'] ?? '',
            ];
            if ($this->model('Absensi_model')->addAbsensi($payload) > 0) {
                $this->redirectGuru('absensi', 'Absensi Berhasil Ditambahkan', 'success');
            }
            $this->redirectGuru('absensi', 'Absensi Gagal Ditambahkan', 'danger');
        }

        public function updateAbsensi() {
            $base = $this->validateBaseInput('absensi');
            $idAbsensi = (int) ($_POST['id_absensi'] ?? 0);
            $status = trim((string) ($_POST['status_kehadiran'] ?? ''));
            if ($idAbsensi <= 0 || !in_array($status, ['Hadir', 'Izin', 'Sakit', 'Alpha'], true)) {
                $this->redirectGuru('absensi', 'Data absensi tidak valid', 'danger');
            }
            $model = $this->model('Absensi_model');
            if (!$model->getByIdAndGuru($idAbsensi, $base['id_guru'])) {
                $this->redirectGuru('absensi', 'Data absensi tidak ditemukan', 'danger');
            }
            $model->updateAbsensi($base + [
                'id_absensi' => $idAbsensi,
                'status_kehadiran' => $status,
                'catatan' => $_POST['catatan'] ?? '',
            ]);
            $this->redirectGuru('absensi', 'Absensi Berhasil Diubah', 'success');
        }

        public function deleteAbsensi($id = 0) {
            $idGuru = $this->getLoggedInGuruId();
            if ((int) $id > 0 && $this->model('Absensi_model')->deleteAbsensi((int) $id, $idGuru) > 0) {
                $this->redirectGuru('absensi', 'Absensi Berhasil Dihapus', 'success');
            }
            $this->redirectGuru('absensi', 'Absensi Gagal Dihapus', 'danger');
        }

        public function hafalan() {
            $idGuru = $this->getLoggedInGuruId();
            $data['judul'] = 'Hafalan Santri';
            $data['hafalan'] = $this->model('Hafalan_model')->getAll($idGuru);
            $data['santri'] = $this->model('Santri_model')->getAll();

            $this->view('templates/header_guru', $data);
            $this->view('guru/hafalan', $data);
            $this->view('templates/footer');
        }

        public function tambahHafalan() {
            $base = $this->validateBaseInput('hafalan');
            $surah = trim((string) ($_POST['surah'] ?? ''));
            $juz = (int) ($_POST['juz'] ?? 0);
            $ayat = trim((string) ($_POST['ayat'] ?? ''));
            $statusSetoran = trim((string) ($_POST['status_setoran'] ?? ''));
            $nilai = $_POST['nilai_kelancaran'] ?? '';
            if ($surah === '' || $juz < 1 || $juz > 30 || $ayat === '' || !in_array($statusSetoran, ['Lancar', 'Kurang Lancar', 'Belum Lancar'], true) || !is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
                $this->redirectGuru('hafalan', 'Data hafalan tidak valid atau belum lengkap', 'danger');
            }
            $payload = $base + [
                'surah' => $surah,
                'juz' => $juz,
                'ayat' => $ayat,
                'status_setoran' => $statusSetoran,
                'nilai_kelancaran' => $nilai,
            ];
            if ($this->model('Hafalan_model')->addHafalan($payload) > 0) {
                $this->redirectGuru('hafalan', 'Hafalan Berhasil Ditambahkan', 'success');
            }
            $this->redirectGuru('hafalan', 'Hafalan Gagal Ditambahkan', 'danger');
        }

        public function updateHafalan() {
            $base = $this->validateBaseInput('hafalan');
            $idHafalan = (int) ($_POST['id_hafalan'] ?? 0);
            $surah = trim((string) ($_POST['surah'] ?? ''));
            $juz = (int) ($_POST['juz'] ?? 0);
            $ayat = trim((string) ($_POST['ayat'] ?? ''));
            $statusSetoran = trim((string) ($_POST['status_setoran'] ?? ''));
            $nilai = $_POST['nilai_kelancaran'] ?? '';
            if ($idHafalan <= 0 || $surah === '' || $juz < 1 || $juz > 30 || $ayat === '' || !in_array($statusSetoran, ['Lancar', 'Kurang Lancar', 'Belum Lancar'], true) || !is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
                $this->redirectGuru('hafalan', 'Data hafalan tidak valid atau belum lengkap', 'danger');
            }
            $model = $this->model('Hafalan_model');
            if (!$model->getByIdAndGuru($idHafalan, $base['id_guru'])) {
                $this->redirectGuru('hafalan', 'Data hafalan tidak ditemukan', 'danger');
            }
            $model->updateHafalan($base + [
                'id_hafalan' => $idHafalan,
                'surah' => $surah,
                'juz' => $juz,
                'ayat' => $ayat,
                'status_setoran' => $statusSetoran,
                'nilai_kelancaran' => $nilai,
            ]);
            $this->redirectGuru('hafalan', 'Hafalan Berhasil Diubah', 'success');
        }

        public function deleteHafalan($id = 0) {
            $idGuru = $this->getLoggedInGuruId();
            if ((int) $id > 0 && $this->model('Hafalan_model')->deleteHafalan((int) $id, $idGuru) > 0) {
                $this->redirectGuru('hafalan', 'Hafalan Berhasil Dihapus', 'success');
            }
            $this->redirectGuru('hafalan', 'Hafalan Gagal Dihapus', 'danger');
        }

        public function pelanggaran() {
            $idGuru = $this->getLoggedInGuruId();
            $data['judul'] = 'Pelanggaran Santri';
            $data['pelanggaran'] = $this->model('Pelanggaran_model')->getAll($idGuru);
            $data['santri'] = $this->model('Santri_model')->getAll();

            $this->view('templates/header_guru', $data);
            $this->view('guru/pelanggaran', $data);
            $this->view('templates/footer');
        }

        public function tambahPelanggaran() {
            $base = $this->validateBaseInput('pelanggaran');
            $jenis = trim((string) ($_POST['jenis_pelanggaran'] ?? ''));
            $sanksi = trim((string) ($_POST['sanksi'] ?? ''));
            $statusSanksi = trim((string) ($_POST['status_sanksi'] ?? ''));
            if ($jenis === '' || $sanksi === '' || !in_array($statusSanksi, ['Belum Selesai', 'Selesai'], true)) {
                $this->redirectGuru('pelanggaran', 'Data pelanggaran tidak valid atau belum lengkap', 'danger');
            }
            $payload = $base + [
                'jenis_pelanggaran' => $jenis,
                'sanksi' => $sanksi,
                'status_sanksi' => $statusSanksi,
            ];
            if ($this->model('Pelanggaran_model')->addPelanggaran($payload) > 0) {
                $this->redirectGuru('pelanggaran', 'Pelanggaran Berhasil Ditambahkan', 'success');
            }
            $this->redirectGuru('pelanggaran', 'Pelanggaran Gagal Ditambahkan', 'danger');
        }

        public function updatePelanggaran() {
            $base = $this->validateBaseInput('pelanggaran');
            $idPelanggaran = (int) ($_POST['id_pelanggaran'] ?? 0);
            $jenis = trim((string) ($_POST['jenis_pelanggaran'] ?? ''));
            $sanksi = trim((string) ($_POST['sanksi'] ?? ''));
            $statusSanksi = trim((string) ($_POST['status_sanksi'] ?? ''));
            if ($idPelanggaran <= 0 || $jenis === '' || $sanksi === '' || !in_array($statusSanksi, ['Belum Selesai', 'Selesai'], true)) {
                $this->redirectGuru('pelanggaran', 'Data pelanggaran tidak valid atau belum lengkap', 'danger');
            }
            $model = $this->model('Pelanggaran_model');
            if (!$model->getByIdAndGuru($idPelanggaran, $base['id_guru'])) {
                $this->redirectGuru('pelanggaran', 'Data pelanggaran tidak ditemukan', 'danger');
            }
            $model->updatePelanggaran($base + [
                'id_pelanggaran' => $idPelanggaran,
                'jenis_pelanggaran' => $jenis,
                'sanksi' => $sanksi,
                'status_sanksi' => $statusSanksi,
            ]);
            $this->redirectGuru('pelanggaran', 'Pelanggaran Berhasil Diubah', 'success');
        }

        public function deletePelanggaran($id = 0) {
            $idGuru = $this->getLoggedInGuruId();
            if ((int) $id > 0 && $this->model('Pelanggaran_model')->deletePelanggaran((int) $id, $idGuru) > 0) {
                $this->redirectGuru('pelanggaran', 'Pelanggaran Berhasil Dihapus', 'success');
            }
            $this->redirectGuru('pelanggaran', 'Pelanggaran Gagal Dihapus', 'danger');
        }
    }
