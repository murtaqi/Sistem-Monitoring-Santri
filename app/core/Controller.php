<?php
    class Controller {
        public function view($view, $data = []){
            require_once '../app/views/'.$view.'.php';
        }

        public function model($model){
            require_once '../app/models/'.$model.'.php';
            return new $model;
        }

        public function isLoggedIn(){
            return (isset($_SESSION['login']) && $_SESSION['login'] === true || isset($_SESSION['role']));
        }

        public function getRole(){
            return $_SESSION['role'] ?? null;
        }

        public function redirectToDashboard(){
            $role = $this->getRole() ?? '';
            switch ($role) {
                case 'admin':
                    header('Location: '. BASEURL . '/Admin');
                    exit;
                case 'guru':
                    header('Location: '. BASEURL . '/Guru');
                    exit;
                case 'ortu':
                    header('Location: '. BASEURL . '/Ortu');
                    exit;
                default:
                    header('Location: '. BASEURL . '/Auth');
                    exit;
            }

        }

        public function authorize($role){
            if(!$this->isLoggedIn()){
                header('Location: '. BASEURL . '/Auth');
                exit;
            }
            $currentRole = $this->getRole() ?? '';
            if($currentRole !== $role){
                $this->redirectToDashboard();
            }
        }

        public function authAdmin(){
            $this->authorize('admin');
        }

        public function authGuru(){
           $this->authorize('guru');
        }

        public function authOrtu(){
            $this->authorize('ortu');
        }

        public function setSession($key, $value){
            $_SESSION[$key] = $value;
        }

        public function getSession($key){
            if(isset($_SESSION[$key])){
                $message = $_SESSION[$key];
                unset($_SESSION[$key]);
                return $message;
            }
            return null;
        }
    }
