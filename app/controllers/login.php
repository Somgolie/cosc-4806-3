<?php

class Login extends Controller {

    public function index() {		//add message for invalid login
			$message = $_SESSION['login_message'] ?? '';
			unset($_SESSION['login_message']);
			$this->view('login/index', ['message' => $message]);
    }
    
    public function verify(){
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];

		
			$user = $this->model('User');
			$user->authenticate($username, $password); 
    }

}
