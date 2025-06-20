<?php

class Signup extends Controller{
    public function index() {
      $message='';
      $user = $this->model('User');
      $data = $user->test();

        $this->view('home/index');
        die;
    }
}
