<?php

class Signup extends Controller {

    public function index(){
        $message = $_SESSION['signup_message'] ?? '';
        unset($_SESSION['signup_message']); // clear message after showing

        $this->view('Signup/index', ['message' => $message]);
    }
}