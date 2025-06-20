<?php

class Signup {
    public function index() {
        $message = "";
        require_once '../app/views/Signup/index.php';
    }

    public function create() {
        // Leave empty for now
        echo "Signup form submitted";
    }
}