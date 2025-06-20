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
            if (!is_password_strong($password)) {   // Check if password is strong
                $_SESSION['signup_message'] = "Password must be at least 5 characters and contain a number.";
                 header("Location: /signup");
                 exit;
            } 
            elseif ($user->username_exists($username)) { // Check if username already exists
                $_SESSION['signup_message'] = "Username already taken. Please choose another.";
                header("Location: /signup");
                exit;
            } 
            else {  // Create user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                if ($user->create_user($username, $hashedPassword)) {
                    header("Location: /login");
                    exit;
                }
        }
        }
        else {
        $_SESSION['signup_message'] = "Please fill in both fields.";
        header("Location: /signup");
        exit;
    }
}
}