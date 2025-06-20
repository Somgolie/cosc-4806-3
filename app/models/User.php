<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        
    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

  public function authenticate($username, $password) {
      $username = strtolower($username);
      $db = db_connect();
      $statement = $db->prepare("SELECT * FROM users WHERE username = :name");
      $statement->bindValue(':name', $username);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($password, $user['password'])) {
          $_SESSION['auth'] = 1;
          $_SESSION['username'] = ucwords($username);
          unset($_SESSION['failedAuth']);
          $this->log_attempt($username, true);
          header('Location: /home');
          exit;
      } else {
          // log bad attempt
          $this->log_attempt($username, false);

          $_SESSION['login_message'] = "Invalid login, try again.";

          if (isset($_SESSION['failedAuth'])) {
              $_SESSION['failedAuth']++;
          } else {
              $_SESSION['failedAuth'] = 1;
          }

          header('Location: /login');
          exit;
      }
  }
  public function username_exists($username) {
      $db = db_connect();
      $statement = $db->prepare("SELECT * FROM users WHERE username = :username");
      $statement->bindValue(':username', strtolower($username));
      $statement->execute();
      return $statement->fetch(PDO::FETCH_ASSOC) !== false;
  }

  public function create_user($username, $hashedPassword) {
      $db = db_connect();
      $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
      $statement->bindValue(':username', strtolower($username));
      $statement->bindValue(':password', $hashedPassword);
      return $statement->execute();
  }
  public function log_attempt($username, $result) {
      $db = db_connect();
      $statement = $db->prepare("INSERT INTO login_Attempts (username, attempt, time) VALUES (:username, :attempt, NOW())");
      $statement->bindValue(':username', strtolower($username));
      $statement->bindValue(':attempt', $result); // 'good' or 'bad'
      return $statement->execute();
  }
}
