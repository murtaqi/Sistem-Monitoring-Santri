<?php
    class Auth Extends Controller {
        public function index() {
            if($this->isLoggedIn()){
                $this->redirectToDashboard();
            }
            $this->view('auth/login');
        }

        public function login(){
            $user = $this->model('Users_model')->getUserByUsername($_POST['username']);
            if($user){
                if(password_verify($_POST['password'], $user['password'])){
                    $_SESSION['login'] = true;
                    $_SESSION['user'] = $user['nama_lengkap'];
                    $_SESSION['role'] = $user['role'];
                    $this->redirectToDashboard();
                }
            }
            Flasher::setFlash('Username atau Password salah', 'danger');
            header('Location: '.BASEURL.'/Auth');
            exit;
        }

        public function logout(){
            $_SESSION = [];
            session_unset();
            session_destroy();
            header('Location: '.BASEURL);
            exit;
        }
    }