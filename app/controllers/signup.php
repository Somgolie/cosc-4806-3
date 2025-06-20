<?php

class Signup extends Controller {

    public function index(){
        $message = $_SESSION['signup_message'] ?? '';
        unset($_SESSION['signup_message']); // clear message after showing

        $this->view('Signup/index', ['message' => $message]);
    }
    public function create() {
        $user = $this->model('User');
        
        function is_password_strong($password) {
         return strlen($password) >= 5 && preg_match('/\d/', $password);
        }
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (!empty($username) && !empty($password)) {
            ///stuff
        }
        else {
        $_SESSION['signup_message'] = "Please fill in both fields.";
    }
}
}